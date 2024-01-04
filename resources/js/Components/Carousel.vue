<template>
    <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-56 md:h-screen mx-2 sm:mx-0">
            <!-- Carousel items -->
            <div v-for="(item, index) in items" :key="index" :class="{ 'hidden': currentIndex !== index }" class="duration-700 ease-in-out" data-carousel-item>
                <img :src="item.image" class="absolute inset-0 w-full h-full object-cover" alt="Carousel Item"/>

                <!-- Text and button animation -->
                <transition name="fade" mode="out-in">
                    <div :key="currentIndex" class="text-container">
                        <h1 class="text-white text-4xl md:text-9xl font-bold mb-4">
                            Sample Collection
                        </h1>
                        <button class="rounded-full bg-black text-white px-4 py-2 md:px-6 md:py-3 text-base md:text-xl">
                            Discover the Collection
                        </button>
                    </div>
                </transition>
            </div>
        </div>

        <!-- Slider indicators -->
        <div class="absolute z-30 flex flex-col items-end top-1/2 right-4 transform -translate-y-1/2 space-y-3">
            <button
            v-for="(indicator, index) in items"
            :key="index"
            type="button"
            class="w-3 h-3 rounded-full"
            :class="{ 'bg-blue-500': currentIndex === index, 'bg-gray-300': currentIndex !== index }"
            :aria-current="currentIndex === index"
            :aria-label="`Slide ${index + 1}`"
            :data-carousel-slide-to="index"
            @click="goToSlide(index)"
            ></button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            items: [
                { image: '/images/Mannequins.jpg' },
                { image: '/images/bg-masthead2.jpg' },
                { image: '/images/bg-masthead3.jpg' },
                { image: '/images/bg-masthead4.jpg' },
                { image: '/images/bg-masthead5.jpg' },
            ],
            currentIndex: 0,
            };
        },
        methods: {
            goToSlide(index) {
            this.currentIndex = index;
            },
            nextSlide() {
            this.currentIndex = (this.currentIndex + 1) % this.items.length;
            },
            startAutoSlide() {
            // Set interval to change slide every 3 seconds
            setInterval(() => {
                this.nextSlide();
            }, 5000);
            },
        },
        mounted() {
            // Start automatic slide change when the component is mounted
            this.startAutoSlide();
        },
    };
</script>

<style>
    .text-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.9s;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>
