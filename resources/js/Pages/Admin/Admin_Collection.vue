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
    <Head title="COLLECTION" />
    <!-- <AdminNavbar /> -->

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Runway Collection</h2>

            <div class="flex items-center space-x-4">
                <!-- {{-- Access for buttons --}} -->
                <a href="#" class="text-gray-800 hover:text-gray-600" @click.prevent="addCollection">
                    <i class="fas fa-plus-circle"></i> ADD COLLECTION
                </a>
                <a href="#" class="text-gray-800 hover:text-gray-600" @click.prevent="addCategory">
                    <i class="fas fa-folder-plus"></i> ADD CATEGORY
                </a>
                <a href="{{ route('collection.type') }}" class="text-gray-800 hover:text-gray-600">
                    <i class="fas fa-tags"></i> ADD TYPE
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
                    <h1 class="text-2xl font-bold"><i class="fas fa-list-alt"></i> Collections</h1>
                </div>
                <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                    <thead>
                      <tr>
                        <th class="bg-black text-white text-left p-2 border-b border-gray-300 border text-center">
                          <input type="checkbox" v-model="selectAll" @change="selectAllItems" />
                        </th>
                        <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Collection Name</th>
                        <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Categories</th>
                        <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Active Status</th>
                        <th class="bg-black text-white text-left p-2 border-b border-gray-300 px-4 py-2 border">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in tableData_Collection" :key="item.id">
                        <td class="p-2 border-b border-gray-300 border text-center">
                          <input type="checkbox" v-model="item.isSelected" />
                        </td>
                        <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                          <a :href="`/view_collection/${item.id}`">
                            <h2 class="text-lg font-semibold">{{ item.name }}</h2>
                          </a>
                        </td>
                        <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                            <div class="flex flex-wrap gap-2">
                                <template v-if="Array.isArray(item.categories)">
                                    <div v-for="categoryId in item.categories" :key="categoryId" class="p-2 bg-gray-200 rounded-full">
                                        <h2 class="text-lg font-semibold">{{ findCategoryById(categoryId)?.name || 'None' }}</h2>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="p-2 bg-gray-200 rounded-full">
                                        <h2 class="text-lg font-semibold">{{ findCategoryById(item.categories)?.name || 'None' }}</h2>
                                    </div>
                                </template>
                            </div>
                        </td>

                        <td class="p-2 border-b border-gray-300 px-2 py-2 border">
                            <span :class="getStatusClass(item.activeStatus)">
                                {{ getStatusLabel(item.activeStatus) }}
                            </span>
                        </td>
                        <td class="border-b border-gray-300 border">
                            <a class="m-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded" :href="`/edit_collection/${item.id}`">
                                Edit
                            </a>
                            <button
                                v-if="item.activeStatus == '1'"
                                class="m-2 bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded"
                                @click="deactivateCollection(item.id, item.activeStatus)"
                            >
                                Deactivate
                            </button>
                            <button
                                v-else
                                class="m-2 bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded"
                                @click="activateCollection(item.id, item.activeStatus)"
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
// import TextInput from 'vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/TextInput';

export default {
    components: {
        Table,
        TextInput,
    },
    mounted() {
        this.fetchData();
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
                    console.error('Error fetching category data:', error);
                });

            // Fetch data for collection
            axios.get('/collection_admin')
                .then(response => {
                    this.tableHeaders_Collection = Object.keys(response.data[0]);
                    this.tableData_Collection = response.data;

                    // Now that the data is fetched, you can safely use findCategoryById
                    this.findCategoryById = this.createFindCategoryByIdMethod();

                })
                .catch(error => {
                    console.error('Error fetching collection data:', error);
                });
        },

        createFindCategoryByIdMethod() {
            return categoryId => {
                // Find category in data_Categories based on category ID
                return this.tableData_Categories.find(category => category.id == categoryId);
            };
        },
        getStatusLabel(activeStatus) {
            switch (activeStatus) {
                case '1':
                    return 'Active';
                case '0':
                    return 'Inactive';
                case '2':
                    return 'Pending';
                default:
                    return 'Unknown';
            }
        },
        getStatusClass(activeStatus) {
            switch (activeStatus) {
                case '1':
                    return 'bg-green-500 text-white rounded-full px-2 py-1';
                case '0':
                    return 'bg-red-500 text-white rounded-full px-2 py-1';
                case '2':
                    return 'bg-yellow-500 text-black rounded-full px-2 py-1';
                default:
                    return 'bg-gray-500 text-white rounded-full px-2 py-1';
            }
        },
        addCollection() {
            this.$inertia.visit(route('add.collection'));
        },
        addCategory() {
            this.$inertia.visit(route('add.category'));
        },
        deactivateCollection(collectionId) {
            if (confirm('Are you sure you want to deactivate this collection?')) {
                axios.post(`/deactivate_collection/${collectionId}`)
                    .then(response => {
                        // Handle success, maybe show a success message or update the UI
                        console.log('Collection deactivated:', response.data);
                        location.reload();
                    })
                    .catch(error => {
                        // Handle error, show error message, etc.
                        console.error('Error deactivating collection:', error);
                    });
            }
        },
        activateCollection(collectionId) {
            if (confirm('Are you sure you want to activate this collection?')) {
                axios.post(`/activate_collection/${collectionId}`)
                    .then(response => {
                        // Handle success, maybe show a success message or update the UI
                        console.log('Collection activated:', response.data);
                        location.reload();
                    })
                    .catch(error => {
                        // Handle error, show error message, etc.
                        console.error('Error activating collection:', error);
                    });
            }
        },
        // async submitForm() {
        //     try {
        //         const response = this.$inertia.post('/collection-product', this.form);
        //         console.log('Success:', response);
        //         // Redirect or update state as needed
        //     } catch (error) {
        //         console.error('Error:', error.response);
        //         // Handle error, show error message, etc.
        //     }
        // },
    },
};
</script>

