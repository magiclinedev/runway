
<template>
    <div class="container mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div v-for="item in tableData_Collection" :key="item.id" class="bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('/storage/${encodeURIComponent(item.image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }">
                    <h2 class="text-lg font-semibold bg-black bg-opacity-50 text-white p-40">{{ item.name }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
// import TextInput from 'vendor/laravel/breeze/stubs/inertia-react/resources/js/Components/TextInput';

export default {
    components: {
    },
    data() {
        return {
            tableHeaders_Collection: [], // Corrected variable name
            tableData_Collection: [],    // Corrected variable nam
        };
    },
    mounted() {
        this.fetchData(); // Fetch your data from the server
    },
    methods: {
        fetchData() {
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
    },
};
</script>
