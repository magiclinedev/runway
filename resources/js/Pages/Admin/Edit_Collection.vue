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
     <Head :title="`Edit Collection`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit {{collection.name}}</h2>
        </template>
        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <div class="mt-4">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data">
                        <!-- Edit Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input v-model="formData.name" type="text" id="name" name="name" class="mt-1 p-2 border rounded-md w-full" />
                        </div>


                        <!-- Display Current Image -->
                        <div class="mb-4" v-if="collection.image">
                            <label class="block text-sm font-medium text-gray-700">Current Image</label>
                            <img :src="decodeURIComponent(`/storage/${encodeURIComponent(collection.image)}`)" alt="Current Image" class="mt-2 w-80 h-full object-cover rounded" />
                        </div>

                        <!-- Edit Image -->
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">New Image</label>
                            <input @change="handleFileChange" type="file" id="image" name="image" class="mt-1 p-2 border rounded-md w-full" ref="fileInput" />
                            <div v-if="selectedImageUrl">
                                <img :src="selectedImageUrl" alt="Selected Image" class="mt-2 w-full h-40 object-cover rounded" />
                                <button @click="removeImage" class="mt-2 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Remove Image</button>
                            </div>
                        </div>

                        <!-- Show Categories (as checkboxes) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Categories</label>
                            <div v-for="category in data_Categories" :key="category.id">
                                <input
                                    type="checkbox"
                                    :id="`category_${category.id}`"
                                    :value="category.id"
                                    v-model="formData.categories"
                                    class="mr-2"
                                />
                                <label :for="`category_${category.id}`">{{ category.name }}</label>
                            </div>
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
            data_Categories: [],    // Corrected variable name
            formData: {
                name: '',
                image: '',
                categories: [], // Initialize category_id as null
            },
            selectedFile: null,
            selectedImageUrl: null,
        };
    },
    mounted() {
        this.fetchData();
        this.formData.name = this.collection.name;
        this.formData.categories = this.collection.categories;// Fetch your data from the server
    },
    methods: {
        async fetchData() {
            try {
                const response = await axios.get('/categories');
                this.data_Categories = response.data;

                // Populate formData.categories with the category IDs from the collection
                this.formData.categories = this.collection.categories;
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
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

        async submitForm() {
            console.log('Form Data:', this.formData);

            // Check if a new image is selected
            const isNewImageSelected = this.selectedFile !== null;
            this.formData.categories = this.formData.categories.map(categoryId => parseInt(categoryId, 10)); // Ensure category IDs are integers

            // If a new image is selected or other form data has changed
            if (isNewImageSelected || this.formData.name !== this.collection.name || JSON.stringify(this.formData.categories) !== JSON.stringify(this.collection.categories.map(category => category.id))) {
                try {
                    // Include categories in the formData to be sent to the server
                    // this.formData.categories = this.formData.categories.map(categoryId => parseInt(categoryId, 10)); // Ensure category IDs are integers
                    const response = await this.$inertia.post(route('update.collection', { id: this.collection.id }), this.formData);

                    console.log('Success:', response.data);
                    // Redirect or update state as needed using Inertia.js
                    this.$inertia.visit(route('dashboard'));
                } catch (error) {
                    console.error('Error:', error.response);
                    // Handle error, show error message, etc.
                }
            } else {
                // Nothing has changed, you might want to notify the user or take appropriate action
                console.log('No changes made. Nothing to submit.');
            }
        },


    },
};

</script>



