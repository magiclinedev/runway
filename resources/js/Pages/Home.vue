<script setup>
import { Head, Link, } from '@inertiajs/vue3';
import NavbarLayout from '@/Layouts/NavbarLayout.vue';
import Display from '@/Components/Display.vue';
import Carousel from '@/Components/Carousel.vue';
import Home_Collection from '@/Components/Home_Collection.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Home" />
    <NavbarLayout />

    <Carousel />

    <Display/>

    <Home_Collection />
</template>


