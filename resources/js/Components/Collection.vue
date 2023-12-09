<script setup>
import Table from '@/Components/Table.vue';

defineProps({
    canLogout: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <div class="container mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
            <div class="flex items-center mb-2">
                <h1 class="text-2xl font-bold"><i class="fas fa-list-alt"></i> Collections</h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Display the latest collection taking the whole row -->
                <div v-if="tableData_Collection.length > 0" :key="tableData_Collection[0].id" class="col-span-3 bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('/storage/${encodeURIComponent(tableData_Collection[0].image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }">
                    <h2 class="text-lg font-semibold bg-black bg-opacity-50 text-white p-80">{{ tableData_Collection[0].name }}</h2>
                </div>
                <!-- Display the second latest collection taking two columns -->
                <div v-if="tableData_Collection.length > 1" :key="tableData_Collection[1].id" class="row-span-2 col-span-2 bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('/storage/${encodeURIComponent(tableData_Collection[1].image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }">
                    <h2 class="text-lg font-semibold bg-black bg-opacity-50 text-white p-60">{{ tableData_Collection[1].name }}</h2>
                </div>
                <!-- Display the rest of the collections in the grid -->
                <div v-for="(item, index) in tableData_Collection.slice(2)" :key="item.id" class="bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('/storage/${encodeURIComponent(item.image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }">
                    <h2 class="text-lg font-semibold bg-black bg-opacity-50 text-white p-40">{{ item.name }}</h2>
                </div>
            </div>
        </div>
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
