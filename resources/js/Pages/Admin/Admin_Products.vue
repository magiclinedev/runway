<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
// import { InertiaForm } from '@inertiajs/inertia-vue3';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="COLLECTION" />
    <!-- <AdminNavbar /> -->

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Runway Products</h2>

            <div class="flex items-center space-x-4">
                <!-- {{-- Access for buttons --}} -->
                <a href="#" class="text-gray-800 hover:text-gray-600" @click.prevent="addProduct">
                    <i class="fas fa-plus-circle"></i> ADD PRODUCT
                </a>
                <a href="#" class="text-gray-800 hover:text-gray-600" @click.prevent="addCollection">
                    <i class="fas fa-plus-circle"></i> ADD COLLECTION
                </a>
                <a href="#" class="text-gray-800 hover:text-gray-600" @click.prevent="addCategory">
                    <i class="fas fa-folder-plus"></i> ADD CATEGORY
                </a>
                <!-- {{-- Trashcan Button --}} -->
                <div class="ml-2">
                    <a href="{{ route('collection.trashcan') }}" class="text-gray-800 hover:text-gray-600">
                        <i class="fas fa-trash-alt"></i> TRASH
                        <span class="badge"></span>
                    </a>
                </div>
            </div>
        </template>
        <div class="container mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <div class="flex items-center mb-2">
                    <h1 class="text-2xl font-bold"><i class="fas fa-list-alt"></i> Products</h1>
                </div>
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead>
                        <tr>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 border text-center">
                                <input type="checkbox" v-model="selectAll" @change="selectAllItems" />
                            </th>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Product Image</th>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Item Reference</th>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Collection</th>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Category</th>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Status</th>
                            <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Action</th>
                        </tr>
                    </thead>
                    <!-- Inside the <tbody> tag, use v-for to loop through your product data -->
                    <tbody>
                        <tr v-for="item in filteredProducts" :key="item.id">
                            <td class="p-2 border-b border-gray-300 border text-center">
                                <input type="checkbox" v-model="item.isSelected" />
                            </td>
                            <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                                <div v-if="item.images">
                                    <img :src="decodeURIComponent(`/storage/${item.images}`)" alt="Product Image" class="max-w-10% h-20" />
                                </div>
                                <div v-else>
                                    No image
                                </div>
                            </td>
                            <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                                <a :href="`/view_product/${item.id}`">
                                    <h2 class="text-lg font-semibold">{{ item.item_reference }}</h2>
                                </a>
                            </td>
                            <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                                <div class="flex flex-wrap gap-2">
                                    <div class="p-2 bg-gray-200 rounded-full">
                                        <h2 class="text-lg font-semibold">{{ findCollectionById(item.collection_id)?.name || 'None' }}</h2>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                                <div class="flex flex-wrap gap-2">
                                    <div class="p-2 bg-gray-200 rounded-full">
                                        <h2 class="text-lg font-semibold">{{ findCategoryById(item.category_id)?.name || 'None' }}</h2>
                                    </div>
                                </div>
                            </td>

                            <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                                <span :class="getStatusClass(item.status)">
                                    {{ getStatusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                                <a class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" :href="`/edit_product/${item.id}`">
                                    Edit
                                </a>
                                <button
                                v-if="item.status == '1'"
                                class="m-2 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"
                                @click="deactivateProduct(item.id, item.status)"
                            >
                                Deactivate
                            </button>
                            <button
                                v-else
                                class="m-2 bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded"
                                @click="activateProduct(item.id, item.status)"
                            >
                                Activate
                            </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import axios from 'axios';

export default {
    components: {
        Table,
    },
    mounted() {
    },
    data() {
        return {
            tableHeaders_Products: [], // Define your product table headers
            tableData_Products: [],    // Define your product table data

            tableHeaders_Collection: [], // Corrected variable name
            tableData_Collection: [],    // Corrected variable name

            tableHeaders_Categories: [], // Corrected variable name
            tableData_Categories: [],    // Corrected variable name

            filteredProducts: [],
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

                // Now that the data is fetched, you can safely use findCategoryById
                this.findCategoryById = this.createFindCategoryByIdMethod();
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
                    console.error('Error fetching category data:', error);
                });

            // Fetch data for collection
            axios.get('/collection_admin')
                .then(response => {
                    this.tableHeaders_Collection = Object.keys(response.data[0]);
                    this.tableData_Collection = response.data;
                })
                .catch(error => {
                    console.error('Error fetching collection data:', error);
                });
        },
        addProduct() {
            this.$inertia.visit(route('add.product'));
        },
        addCollection() {
            this.$inertia.visit(route('add.collection'));
        },
        addCategory() {
            this.$inertia.visit(route('add.category'));
        },


        createFindCategoryByIdMethod() {
            return categoryId => {
                // Find category in data_Categories based on category ID
                return this.tableData_Categories.find(category => category.id == categoryId);
            };
        },
        findCollectionById(collectionId) {
            // Find collection in tableData_Collection based on collection ID
            return this.tableData_Collection.find(collection => collection.id == collectionId);
        },
        getStatusLabel(status) {
            switch (status) {
                case 0:
                    return 'Inactive';
                case 1:
                    return 'Active';
                case 3:
                    return 'Thumbnail';
                default:
                    return 'Unknown';
            }
        },
        getStatusClass(status) {
            switch (status) {
                case 1:
                    return 'bg-green-500 text-white rounded-full px-2 py-1';
                case 0:
                    return 'bg-red-500 text-white rounded-full px-2 py-1';
                case 2:
                    return 'bg-yellow-500 text-black rounded-full px-2 py-1';
                default:
                    return 'bg-gray-500 text-white rounded-full px-2 py-1';
            }
        },
        deactivateProduct(productId) {
            if (confirm('Are you sure you want to deactivate this product?')) {
                axios.post(`/deactivate_product/${productId}`)
                    .then(response => {
                        // Handle success, maybe show a success message or update the UI
                        console.log('product deactivated:', response.data);
                        location.reload();
                    })
                    .catch(error => {
                        // Handle error, show error message, etc.
                        console.error('Error deactivating product:', error);
                    });
            }
        },
        activateProduct(productId) {
            if (confirm('Are you sure you want to activate this product?')) {
                axios.post(`/activate_product/${productId}`)
                    .then(response => {
                        // Handle success, maybe show a success message or update the UI
                        console.log('product activated:', response.data);
                        location.reload();
                    })
                    .catch(error => {
                        // Handle error, show error message, etc.
                        console.error('Error activating product:', error);
                    });
            }
        },
    },
    computed: {
        filteredProducts() {
            return this.tableData_Products.filter(item => item.status !== 3);
        },
    },
};
</script>

