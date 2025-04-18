<template>
    <header
        :class="{
            'bg-card sticky top-5 z-40 mx-auto mt-4 flex w-[90%] items-center justify-between rounded-2xl border p-2 shadow-md md:w-[70%] lg:w-[75%] lg:max-w-screen-xl': true,
        }"
    >
        <a href="/" class="flex items-center gap-2 text-lg font-bold">
            <!-- Ensure logo is in the public folder -->
            <img src="/logo.jpg" alt="Logo Seminario Juvenil" class="h-12" />
            Seminario Juvenil
        </a>
    </header>
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto grid place-items-center gap-8 py-20 md:py-32 lg:max-w-screen-xl">
            <div class="space-y-8 text-center">
                <div class="mx-auto max-w-screen-md text-center text-5xl font-bold md:text-6xl">
                    <h1>¡Inscripción enviada con éxito! Nos pondremos en contacto pronto.</h1>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';

onMounted(() => {
    // Ensure light mode is enforced if needed
    const htmlElement = document.documentElement;
    if (htmlElement.classList.contains('dark')) {
        htmlElement.classList.remove('dark');
    }
    htmlElement.classList.add('light');
});
</script>

<style scoped>
/* Keep existing animations if they are used elsewhere, otherwise they can be removed */
.img-shadow-animation {
    animation-name: img-shadow-animation;
    animation-iteration-count: infinite;
    animation-duration: 2s;
    animation-timing-function: linear;
    animation-direction: alternate;
}

.img-border-animation {
    animation-name: img-border-animation;
    animation-iteration-count: infinite;
    animation-duration: 2s;
    animation-timing-function: linear;
    animation-direction: alternate;
}

@keyframes img-shadow-animation {
    from {
        opacity: 0.5;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0px);
    }
}
</style>
