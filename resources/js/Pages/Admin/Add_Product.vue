<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
// import { InertiaForm } from '@inertiajs/inertia-vue3';
import TextInput from '@/Components/TextInput.vue';
import Dropdown from '@/Components/Dropdown.vue';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Product</h2>
        </template>

        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm md:rounded-lg mt-4 p-6">
                <!-- Add Product Form or Content Goes Here -->
                <div class="container mx-auto px-4 py-8">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Side -->
                        <div>
                            <!-- Item Reference -->
                            <div class="relative mb-6">
                                <label for="item_reference" class="text-md text-gray-600">Item Reference</label>
                                <input v-model="formData.item_reference" type="text" name="item_reference" id="item_reference" class="block w-full mt-1 px-4 py-3 text-md text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Enter item reference" />
                            </div>

                            <!-- Description Textarea -->
                            <div class="relative mb-6">
                                <label for="description" class="text-md text-gray-600">Description</label>
                                <textarea v-model="formData.description" id="description" name="description" class="block w-full mt-1 px-4 py-3 text-lg text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 h-72 resize-y"></textarea>
                            </div>

                        </div>

                        <!-- Right Side -->
                        <div>
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

                            <!-- Images File Input -->
                            <div class="relative mb-8">
                                <label for="images" class="text-md text-gray-600">Product Image</label>
                                <input type="file" id="images" name="images" class="block w-full mt-1 px-4 py-3 text-md text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" @change="handleFileChange" />
                            </div>

                            <div v-if="selectedFile">
                                <img :src="selectedImageUrl" alt="Selected Image" style="max-width: 300px; max-height: 300px;" />
                                <button @click="removeImage">Remove Image</button>
                            </div>
                            <!-- Images File Input
                            <div class="relative mb-8">
                                <label for="images" class="text-md text-gray-600">Product Gallery</label>
                                <input type="file" id="images" name="images" class="block w-full mt-1 px-4 py-3 text-md text-gray-900 bg-transparent rounded-lg border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" />
                            </div> -->
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="col-span-2 md:col-span-1 mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Product
                        </button>
                    </form>
                </div>
            </div>
        </div>



    </AuthenticatedLayout>
</template>


<script>
import axios from 'axios';

export default {
    components: {
        Table,
        TextInput,
    },
    data() {
        return {
            formData: {
                item_reference: '',
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
        this.fetchData(); // Fetch your data from the server
    },
    methods: {
        fetchData() {
            // Fetch data for collections
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
        handleFileChange(event) {
            this.selectedFile = event.target.files[0];
            // Display selected image preview
            this.selectedImageUrl = URL.createObjectURL(this.selectedFile);
            // Assign the selected file to the formData.image property
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
            // Remove the file reference from formData.image
            this.formData.images = null;
        },
        async submitForm() {
            console.log('Form Data:', this.formData);

            try {
                // Continue with the form submission
                const response = await this.$inertia.post(route('store.product'), this.formData);

                console.log('Success:', response.data);
                // Redirect or update state as needed using Inertia.js
                this.$inertia.visit(route('dashboard'));
            } catch (error) {
                console.error('Error:', error.response);
                // Handle error, show error message, etc.
            }
        },
    },
};
</script>


