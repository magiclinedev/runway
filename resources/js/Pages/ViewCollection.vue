<!-- View Collection's Categories -->
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NavbarLayout from '@/Layouts/NavbarLayout.vue';

defineProps({
    collection: Object,
    products: Array,  // Add this line to define the products prop
});
</script>

<template>
    <Head title="Collection View" />
    <NavbarLayout />

    <div class="container mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
          <div class="flex items-center mb-2">
            <h3 class="text-3xl font-bold mb-4">{{ collection.name }}</h3>
          </div>
          <!-- Display categories -->
          <div class="mt-4 flex flex-wrap">
            <div
              v-for="(categoryId, index) in collection.categories"
              :key="index"
              class="bg-white p-4 mb-4 border rounded-md hover:shadow-md w-full sm:w-80 m-2"
            >
              <!-- Render each category associated with the collection -->
              <div v-if="findCategoryById(categoryId)">
                <a
                  :href="`/collection/${encodeURIComponent(collection.name)}/category/${encodeURIComponent(findCategoryById(categoryId).name)}`"
                >
                  <span class="text-lg font-bold">{{ findCategoryById(categoryId).name }}</span>

                  <!-- Render thumbnails for the current category -->
                  <div class="flex flex-col mt-2">
                    <template v-if="products && products.length > 0">
                      <div
                        v-for="(product, productIndex) in products"
                        :key="productIndex"
                        class="w-full"
                      >
                        <!-- Check if the product has status === 3 and belongs to the current category -->
                        <template v-if="product.status == 3 && product.category_id == categoryId">
                          <img
                            :src="`/storage/${encodeURIComponent(product.images)}`"
                            alt="Thumbnail"
                            class="w-full h-78 object-cover rounded mb-2"
                          />
                        </template>
                      </div>
                    </template>
                    <!-- No need for an else block here -->
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div v-if="collection.categories && collection.categories.length === 0">
            <span class="text-lg font-bold">No categories available</span>
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
