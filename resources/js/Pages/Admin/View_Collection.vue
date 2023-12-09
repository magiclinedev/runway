<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    collection: Object, // Define the collection prop
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
                        <div class="flex items-center mb-4">

                        </div>
                    </div>
                </div>
            </div>

            <!-- CATEGORIES -->
            <div class="mt-4">
                <h4 class="text-xl font-bold mb-2">Categories:</h4>
                <div v-if="collection.categories" class="flex flex-wrap">
                  <!-- Render each category as a card with thumbnail and buttons -->
                  <div v-for="(category, index) in collection.categories" :key="index">
                    <div class="bg-white p-4 mr-2 mb-2 border rounded-md flex-shrink-0">
                      <span class="text-lg font-bold">{{ category }}</span>
                      <div class="mt-2">
                        <!-- Display Current Thumbnail -->
                        <img
                          :src="`/storage/${encodeURIComponent(collection.image)}`"
                          alt="Category Thumbnail"
                          class="w-full h-20 rounded pb-2 w-auto h-44"
                        />
                        <!-- Add Product Button -->
                        <button @click="addProduct(category)" class="bg-green-500 text-white px-2 py-1 rounded">
                          Add Product
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <span class="text-lg font-bold">No categories available</span>
                </div>
              </div>
        </div>
      </AuthenticatedLayout>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    components: {
        Table,
    },
    data() {
        return {
            formData: {
                item_reference: '',
                collection: '',
                categories: '',
                description: '',
            },
            tableHeaders_Products: [], // Dynamically set based on your data
            tableData_Products: [],    // Dynamically set based on your data

            tableHeaders_Collection: [], // Corrected variable name
            tableData_Collection: [],    // Corrected variable name

            tableHeaders_Categories: [], // Corrected variable name
            tableData_Categories: [],    // Corrected variable name

            // dropDownData_Collection: [],
        };
    },
    mounted() {
        this.fetchData(); // Fetch your data from the server
    },
    methods: {
        fetchData() {
            // Fetch data for products
            axios.get('/products')
            .then(response => {
                this.tableHeaders_Products = Object.keys(response.data[0]);
                this.tableData_Products = response.data;
            })
            .catch(error => {
                console.error('Error fetching products data:', error);
            });

            // Fetch data for category
            axios.get('/categories')
            .then(response => {
                this.tableHeaders_Categories = Object.keys(response.data[0]);
                this.tableData_Categories = response.data;
                this.dropDownData_Categories = response.data;
            })
            .catch(error => {
                console.error('Error fetching collection data:', error);
            });

            // Fetch data for collection
            axios.get('/collection')
            .then(response => {
                this.tableHeaders_Collection = Object.keys(response.data[0]);
                this.tableData_Collection = response.data;
            })
            .catch(error => {
                console.error('Error fetching collection data:', error);
            });
        },
        addCollection() {
            this.$inertia.visit(route('add.collection'));
        },
    },
};
</script>
