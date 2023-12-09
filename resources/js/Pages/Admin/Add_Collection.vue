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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Collection</h2>
        </template>

        <div class="container mx-auto">
            <!-- Right Section (Add Product) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
                <!-- Add Product Form or Content Goes Here -->
                <div class="container mx-auto px-4 py-8">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="px-8 py-6">
                        <!-- <InertiaForm :action="{{route('collection.store')}}" method="POST" enctype="multipart/form-data" class="px-8 py-6"> -->
                        <div class="grid grid-cols-2 gap-4">

                            <!-- {{-- Collection Name --}} -->
                            <div class="col-span-2 sm:col-span-1">
                                <div class="relative">
                                    <input v-model="formData.name" type="text" name="name" id="name" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                                    <label for="floating_outlined" class="absolute text-sm text-gray-500  duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white  px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus: peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Collection Name</label>
                                </div>
                            </div>

                            <!-- {{-- Categories --}} -->
                            <div class="col-span-2">
                                <label class="block font-bold mb-2">Category</label>
                                <div class="col-span-2 sm:col-span-1 flex flex-wrap items-start" v-for="category in dropDownData_Categories" :key="category.id">
                                    <div class="mr-4 mb-2">
                                        <input
                                            type="checkbox"
                                            :id="'category_' + category.id"
                                            v-model="formData.categories"
                                            :value="category.id"
                                            class="border rounded-md py-2 px-3 mr-2"
                                        />
                                        <label :for="'category_' + category.id">{{ category.name }}</label>
                                    </div>
                                </div>
                            </div>

                            <!-- {{-- UPLOAD FILES --}} -->
                            <!-- {{-- images --}} -->
                            <div class="col-span-2">
                                <label for="images" class="block font-bold mb-2">Thumbnail <i class="text-sm text-gray-600">(Maximum upload size 2MB)</i></label>
                                <!-- {{-- <input type="file" name="images[]" id="images" class="w-full border rounded-md py-2 px-3" multiple> --}} -->
                                <input type="file" name="image" ref="fileInput" @change="handleFileChange" />
                            </div>

                            <div v-if="selectedFile">
                                <img :src="selectedImageUrl" alt="Selected Image" style="max-width: 300px; max-height: 300px;" />
                                <button @click="removeImage">Remove Image</button>
                            </div>
                        </div>
                        <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Collection
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
                const response = await this.$inertia.post(route('store.collection'), this.formData);

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


