<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    collection: Object,
    products: Array,  // Add this line to define the products prop
});
</script>


<template>
    <div>
      <Head title="View Collection" />
      <AuthenticatedLayout>
        <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">View Collection</h2>
        </template>

        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Display the latest collection taking the whole row -->
                    <div class="col-span-1 bg-gray-100 rounded-md overflow-hidden">
                        <img
                        v-if="collection.image"
                        :src="`/storage/${encodeURIComponent(collection.image)}`"
                        alt="Collection Image"
                        class="w-auto h-96 rounded"
                        />
                    </div>
                    <div class="col-span-2 bg-gray-100 rounded-md overflow-hidden p-10">
                        <h3 class="text-3xl font-bold mb-4">{{ collection.name }}</h3>
                        <p class="text-xl font-bold mb-4"></p>
                         <!-- Categories -->
                         <div class="mt-4">
                            <h4 class="text-xl font-bold mb-2">Categories:</h4>
                            <div v-if="collection.categories && collection.categories.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Render each category associated with the collection -->
                                <div v-for="(categoryId, index) in collection.categories" :key="index">
                                    <!-- Find the corresponding category in data_Categories -->
                                    <div v-if="findCategoryById(categoryId)">
                                        <div class="bg-white p-4 mb-2 border rounded-md flex-shrink-0">
                                            <span class="text-lg font-bold">{{ findCategoryById(categoryId).name }}</span>

                                            <!-- Render thumbnails for the current category -->
                                            <!-- Render thumbnails for the current category -->
                                            <div>
                                                <template v-if="products && products.length > 0">
                                                    <div v-for="(product, productIndex) in products" :key="productIndex">
                                                        <!-- Check if the product has status === 3 and belongs to the current category -->
                                                        <template v-if="product.status == 3 && product.category_id == categoryId">
                                                            <img
                                                                :src="decodeURIComponent(`/storage/${encodeURIComponent(product.images)}`)"
                                                                alt="Thumbnail"
                                                                class="w-auto h-24 rounded mt-2"
                                                            />
                                                        </template>
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <span class="text-sm">No thumbnails available for this category</span>
                                                </template>
                                            </div>

                                            <form @submit.prevent="submitForm(categoryId)" enctype="multipart/form-data" class="px-8 py-6">
                                                <input type="file" name="image" ref="fileInput" @change="handleFileChange" />

                                                <input type="hidden" name="category_id" v-model="formData.category_id" />
                                                <input type="hidden" name="collection_id" v-model="formData.collection_id" />

                                                <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                  Save as Thumbnail
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <span class="text-lg font-bold">No categories available</span>
                            </div>
                        </div>

                    </div>
                </div>

               <!-- Table for displaying products -->
                <div class="mt-4">
                    <h4 class="text-xl font-bold mb-2">Products:</h4>
                    <div v-if="products && products.length > 0">
                        <!-- Replace with your actual table structure -->
                        <table class="min-w-full border divide-y divide-gray-200 bg-white shadow-md rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Name</th>
                                    <th class="py-2 px-4 border-b text-left">Category</th>
                                    <th class="py-2 px-4 border-b text-left">Description</th>
                                    <!-- Add more headers based on your data structure -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in products" :key="index" class="hover:bg-gray-50">
                                    <!-- Add a conditional check for status === 3 -->
                                    <template v-if="product.status !== 3">
                                        <td class="py-2 px-4 border-b">{{ product.item_reference }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <!-- Display the category name instead of the category ID -->
                                            {{ findCategoryById(product.category_id)?.name || 'Unknown Category' }}
                                        </td>
                                        <td class="py-2 px-4 border-b">{{ product.description }}</td>
                                        <!-- Add more cells based on your data structure -->
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <span class="text-lg font-bold">No products available</span>
                    </div>
                </div>
            </div>
        </div>
      </AuthenticatedLayout>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            data_Categories: [],    // Corrected variable name
            formData: {
                image: '',
                category_id: null, // Initialize category_id as null
                collection_id: null, // Initialize collection_id as null
            },
            selectedFile: null,
            selectedImageUrl: null,
        };
    },
    mounted() {
        this.fetchData(); // Fetch your data from the server
    },
    methods: {
        fetchData() {
            // Fetch data for category
            axios.get('/categories')
            .then(response => {
                this.data_Categories = response.data;
            })
            .catch(error => {
                console.error('Error fetching collection data:', error);
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
            // Assign the selected file to the formData.image property
            this.formData.image = this.selectedFile;
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
            this.formData.image = null;
        },
        async submitForm(categoryId) {
            this.formData.category_id = categoryId;
            this.formData.collection_id = this.collection.id;
            console.log('Form Data:', this.formData.category_id = categoryId);

            try {
                // Continue with the form submission
                const response = await this.$inertia.post(route('thumbnail.product'), this.formData);

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
