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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a v-for="item in tableData_Collection" :key="item.id" :href="`/view_collection/${item.id}`">
                    <div class="bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('/storage/${encodeURIComponent(item.image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }">
                        <h2 class="text-lg font-semibold bg-black bg-opacity-50 text-white p-20">{{ item.name }}</h2>
                    </div>
                    </a>
                </div>
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
        addCategory() {
            this.$inertia.visit(route('add.category'));
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

