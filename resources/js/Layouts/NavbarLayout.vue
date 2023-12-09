<script setup>
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

</script>

<template>
    <nav class="flex items-center justify-between p-6">
        <!-- Logo or Branding -->
        <div>
            <Link :href="'/'" class="text-2xl font-bold text-black">RUNWAY</Link>
        </div>

        <!-- Navigation Links -->
        <div class="space-x-4">
            <NavLink :href="'/'" class="navLinks text-black">Home</NavLink>
            <NavLink :href="'Collection'" class="navLinks text-black">Collection</NavLink>
            <NavLink :href="'#'" class="navLinks text-black">Contact Us</NavLink>
            <NavLink :href="'#'" class="navLinks text-black">Search</NavLink>
        </div>
    </nav>
</template>

<style>
nav {
    position: sticky;
    top: 0;
    z-index: 1000;
    background-color: white; /* Set background color as needed */
}
.navLinks {
    font-size: 20px;
}
</style>

