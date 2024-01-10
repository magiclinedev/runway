<!-- Collection in homepage -->
<template>
    <div class="container mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <a v-for="item in tableData_Collection" :key="item.id" :href="`/collection_view/${encodeURIComponent(item.id)}`">
                    <div class="bg-gray-100 rounded-md overflow-hidden" :style="{ backgroundImage: `url('${getImageUrl(item.image)}')`, backgroundPosition: '50% 0%', backgroundSize: 'cover' }">
                      <h2 class="text-lg font-semibold bg-black bg-opacity-50 text-white p-40">{{ item.name }}</h2>
                    </div>
                  </a>
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
        getImageUrl(imagePath) {
            return `/storage/${encodeURIComponent(imagePath).replace(/%2F/g, '/')}`;
        },
    },
};
</script>
