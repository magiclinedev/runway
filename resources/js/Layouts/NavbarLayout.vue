<template>
    <nav class="flex items-center justify-between p-6">
      <!-- Logo or Branding -->
      <div>
        <Link :href="'/'" class="text-2xl font-bold text-black">RUNWAY</Link>
      </div>

      <!-- Hamburger Menu Button (visible on mobile view) -->
      <div class="flex items-center sm:hidden">
        <button
          id="hamburger"
          class="text-black focus:outline-none text-3xl"
          @click="toggleMobileMenu"
        >
          &#9776; <!-- Unicode for hamburger icon -->
        </button>
      </div>

      <!-- Mobile Menu -->
      <div
        v-if="isMobileMenuOpen"
        class="sm:hidden absolute top-16 left-0 right-0 bg-white shadow-lg rounded-b-lg z-10"
      >
        <div class="flex flex-col items-center py-2">
          <NavLink :href="'/'" class="navLinks text-black">
            Home
          </NavLink>
          <NavLink :href="'/Collection'" class="navLinks text-black">
            Collection
          </NavLink>
          <NavLink :href="'#'" class="navLinks text-black">
            Contact Us
          </NavLink>
          <NavLink :href="'#'" class="navLinks text-black">
            Search
          </NavLink>
        </div>
      </div>

      <!-- Navigation Links (visible on desktop view) -->
      <div class="hidden sm:flex sm:space-x-4">
        <NavLink :href="'/'" class="navLinks text-black">Home</NavLink>
        <NavLink :href="'/Collection'" class="navLinks text-black">
          Collection
        </NavLink>
        <NavLink :href="'#'" class="navLinks text-black">Contact Us</NavLink>
        <NavLink :href="'#'" class="navLinks text-black">Search</NavLink>
      </div>
    </nav>
  </template>

  <script setup>
  import { Link } from "@inertiajs/vue3";
  import NavLink from "@/Components/NavLink.vue";
  import { ref } from "vue";

  const isMobileMenuOpen = ref(false);

  function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
  }
  </script>

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
