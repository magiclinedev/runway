<!-- Product Details -->
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NavbarLayout from '@/Layouts/NavbarLayout.vue';

defineProps({
    collection: Object,
    product: Object,
    category: Object,
});
</script>

<template>
    <Head title="Collection View" />
    <NavbarLayout />
        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <div class="flex items-center mb-2">
                    <h3 class="text-3xl font-bold mb-4">{{ collection.name }} / {{ category.name }}</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="flex flex-col gap-4 order-1 sm:order-none">
                        <img
                            :src="`/storage/${encodeURIComponent(product.images)}`"
                            alt="Thumbnail"
                            class="w-40 h-auto object-cover rounded"
                        />
                    </div>
                    <div class="md:col-span-2 sm:col-span-1 rounded-md overflow-hidden">
                        <img
                        :src="`/storage/${encodeURIComponent(product.images)}`"
                        alt="Thumbnail"
                        class="w-full h-auto object-cover rounded"
                        />
                    </div>
                    <div class="md:col-span-2 sm:col-span-1 rounded-md overflow-hidden order-2 sm:order-1">
                        <div class="col-span-2 rounded-md p-10">
                            <p class="text-xl font-bold mb-4"></p>
                            <div class="mt-4">
                                <h1 class="text-6xl font-bold mb-2">{{ product.item_reference }}</h1>
                                <p>{{ product.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    },
};

</script>
