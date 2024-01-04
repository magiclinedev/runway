<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    // collection: Object,
    product: Object,  // Add this line to define the products prop
});
</script>

<template>
     <Head :title="`Edit Product`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{product.item_reference}}</h2>
        </template>

        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <div class="mt-4">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data">
                        <!-- Edit item_reference -->
                        <div class="mb-4">
                            <label for="item_reference" class="block text-sm font-medium text-gray-700">Item Reference</label>
                            <input v-model="formData.item_reference" type="text" id="item_reference" name="item_reference" class="mt-1 p-2 border rounded-md w-full" />
                        </div>

                        <!-- Edit description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea v-model="formData.description" type="text" id="description" name="description" class="block w-full mt-1 px-4 py-3 text-lg text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 h-72 resize-y"></textarea>
                        </div>
                        <!-- CUrrent images -->
                        <div class="mb-4" v-if="product.images">
                            <label class="block text-sm font-medium text-gray-700">Current Images</label>
                            <img :src="`/storage/${encodeURIComponent(product.images)}`" alt="Current Images" class="mt-2 w-80 h-full object-cover rounded" />
                        </div>
                        <!-- New Images -->
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium text-gray-700">New Images</label>
                            <input @change="handleFileChange" type="file" id="images" name="images" class="mt-1 p-2 border rounded-md w-full" ref="fileInput" />
                            <div v-if="selectedImageUrl">
                                <img :src="selectedImageUrl" alt="Selected Images" class="mt-2 w-full h-40 object-cover rounded" />
                                <button @click="removes" class="mt-2 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Remove Images</button>
                            </div>
                        </div>

                        <!-- Collection Dropdown -->
                        <!-- Collection Dropdown -->
                        <div class="relative mb-6">
                            <label for="collection_id" class="text-md text-gray-600">Collection</label>
                            <select v-model="formData.collection_id" id="collection_id" name="collection_id" class="block w-full mt-1 px-4 py-3 text-md text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Select a collection</option>
                            <!-- Loop through collection options -->
                            <option v-for="collection in dropDownData_Collections" :key="collection.id" :value="collection.id">{{ collection.name }}</option>
                            </select>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="relative mb-8">
                            <label for="category_id" class="text-md text-gray-600">Category</label>
                            <select v-model="formData.category_id" id="category_id" name="category_id" class="block w-full mt-1 px-4 py-3 text-md text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Select a category</option>
                            <!-- Loop through category options -->
                            <option v-for="category in dropDownData_Categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>

                        <!-- Save Button -->
                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save Changes</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>


<script>
import axios from 'axios';

export default {
    data() {
        return {
            formData: {
                item_reference: '',
                description: '',
                collection_id: '',
                category_id: '',
                images: '',
            },
            dropDownData_Categories: [],
            dropDownData_Categories: [],
            selectedFile: null,
            selectedImageUrl: null,
        };
    },
    mounted() {
        this.fetchData();
        this.formData.item_reference = this.product.item_reference;
        this.formData.description = this.product.description;
        this.formData.collection_id = this.product.collection_id;
        this.formData.category_id = this.product.category_id;
        // this.formData.categories = this.collection.categories;// Fetch your data from the server
    },
    methods: {
        fetchData() {
            axios.get('/collection')
                .then(response => {
                    this.dropDownData_Collections = response.data;
                })
                .catch(error => {
                    console.error('Error fetching collection data:', error);
                });

            // Fetch data for categories
            axios.get('/categories')
                .then(response => {
                    this.dropDownData_Categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching category data:', error);
                });
        },
        findCategoryById(categoryId) {
            // Find category in data_Categories based on category ID
            return this.data_Categories.find(category => category.id == categoryId);
        },

        handleFileChange(event) {
            this.selectedFile = event.target.files[0];
            // Display selected image preview
            this.selectedImageUrl = URL.createObjectURL(this.selectedFile);
            // Assign the selected file to the formData.images property
            this.formData.images = this.selectedFile;
        },
        removeImage() {
            this.selectedFile = null;
            this.selectedImageUrl = null;

            // Clear the selected file from the input
            const fileInput = this.$refs.fileInput;
            if (fileInput) {
                fileInput.value = '';
            }
            // Remove the file reference from formData.images
            this.formData.images = null;
        },

        async submitForm() {
            console.log('Form Data:', this.formData);
            const response = await this.$inertia.post(route('update.product', { id: this.product.id }), this.formData);

            console.log('Success:', response.data);

        },


    },
};

</script>



