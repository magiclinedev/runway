<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    product: Object, // Add this line to define the products prop
});
</script>


<template>
    <div>
      <Head title="View Collection" />
      <AuthenticatedLayout>
        <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">View {{ product.item_reference }}</h2>
        </template>

        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{ product.item_reference }}<br>
                    {{ product.description }}
                    <img
                    :src="`/storage/${encodeURIComponent(product.images)}`"
                    alt="Thumbnail"
                    class="w-full h-78 object-cover rounded mt-2"
                />
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
