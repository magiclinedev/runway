<!-- Collection's Category's Products -->
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NavbarLayout from '@/Layouts/NavbarLayout.vue';

defineProps({
    category: Object,
    collection: Object,
    products: Array,
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

            <!-- Table for displaying products -->
            <div class="mt-4 flex flex-wrap">
                <template v-if="products && products.length > 0">
                    <template v-for="(product, index) in products">
                        <!-- Check if product status is not 3 (thumbnail) -->
                        <template v-if="product.status !== 3">
                            <a :href="`/collection/${encodeURIComponent(collection.name)}/category/${encodeURIComponent(category.name)}/product/${encodeURIComponent(product.item_reference)}`">
                                <div :key="index" class="bg-white p-4 mb-4 rounded-md hover:shadow-md w-80 m-2">
                                    <h2 class="text-lg font-bold mb-2">{{ product.item_reference }}</h2>
                                    <img
                                        :src="`/storage/${encodeURIComponent(product.images)}`"
                                        alt="Product Image"
                                        class="w-auto h-96 object-cover rounded"
                                    />
                                </div>
                            </a>
                        </template>
                    </template>
                </template>
                <template v-else>
                    <span class="text-lg font-bold">No products available</span>
                </template>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    data() {
        return {
            data_Categories: [],
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get('/categories')
                .then(response => {
                    this.data_Categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching collection data:', error);
                });
        },
        findCategoryById(categoryId) {
            return this.data_Categories.find(category => category.id == categoryId);
        },
    },
    computed: {
        hasNonThumbnailItems() {
            return this.products.some(product => product.status !== 3);
        },
    },
};
</script>
