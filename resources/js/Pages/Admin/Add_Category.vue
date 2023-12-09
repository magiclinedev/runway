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
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Category</h2>
        </template>

        <div class="container mx-auto">
            <!-- Right Section (Add Product) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <!-- Add Product Form or Content Goes Here -->
                <div class="container mx-auto px-4 py-8">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="px-8 py-6">
                        <!-- <InertiaForm :action="{{route('collection.store')}}" method="POST" enctype="multipart/form-data" class="px-8 py-6"> -->
                        <div class="grid grid-cols-2 gap-4">

                            <!-- {{-- Category Name --}} -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="name" class="block font-bold mb-2">Category Name</label>
                                <input v-model="formData.name" type="text" name="name" id="name" class="w-full border rounded-md py-2 px-3" placeholder="Enter Category Name">
                            </div>

                        </div>
                        <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script>
import axios from 'axios';

export default {
    components: {
        Table,
        TextInput,
    },
    data() {
        return {
            formData: {
                name: '',
                categories: [],
                image: '',
            },
            dropDownData_Categories: [],
            selectedFile: null,
            selectedImageUrl: null,
        };
    },
    mounted() {
        this.fetchData(); // Fetch your data from the server
    },
    methods: {
        fetchData() {
            // Fetch data for categories
            axios.get('/categories')
                .then(response => {
                    this.dropDownData_Categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching category data:', error);
                });
        },
        handleFileChange(event) {
            this.selectedFile = event.target.files[0];
            // Display selected image preview
            this.selectedImageUrl = URL.createObjectURL(this.selectedFile);
            // Assign the selected file to the formData.image property
            this.formData.image = this.selectedFile;
        },
        removeImage() {
            this.selectedFile = null;
            this.selectedImageUrl = null;

            // Clear the selected file from the input
            const fileInput = this.$refs.fileInput;
            if (fileInput) {
                fileInput.value = '';
            }
            // Remove the file reference from formData.image
            this.formData.image = null;
        },
        async submitForm() {
            console.log('Form Data:', this.formData);

            try {
                // Continue with the form submission
                const response = await this.$inertia.post(route('store.category'), this.formData);

                console.log('Success:', response.data);
                // Redirect or update state as needed using Inertia.js
                this.$inertia.visit(route('dashboard'));
            } catch (error) {
                console.error('Error:', error.response);
                // Handle error, show error message, etc.
            }
        },
    },
};
</script>


