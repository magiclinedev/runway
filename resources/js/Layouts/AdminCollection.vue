
<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import AdminNavbar from '@/Layouts/AdminNavbar.vue';

    defineProps({
        canLogout: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    });
</script>

<template>
    <div>

    </div>
</template>
