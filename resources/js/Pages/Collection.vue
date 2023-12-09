<script setup>
import { Head, Link } from '@inertiajs/vue3';
import NavbarLayout from '@/Layouts/NavbarLayout.vue';
import Collection from '@/Components/Collection.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Collection" />
    <NavbarLayout />

    <Collection />
</template>

<style>

</style>
