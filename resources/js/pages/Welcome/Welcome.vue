<template>
    <NavBar />
    <Hero />
    <Porpouse />
    <Jovenes />
    <Footer />
</template>

<script lang="ts" setup>
import { onMounted } from 'vue';
import Jovenes from '../../../../public/Jovenes.vue';
import Footer from './Components/Footer.vue';
import Hero from './Components/Hero.vue';
import NavBar from './Components/NavBar.vue';
import Porpouse from './Components/Porpouse.vue';

onMounted(() => {
    document.documentElement.classList.remove('dark'); // Remove dark mode class if applied
    document.documentElement.classList.add('light'); // Enforce light mode
});
</script>

<style></style>
