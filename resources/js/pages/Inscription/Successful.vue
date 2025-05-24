<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

const page = usePage();

// Make the computed property safely access nested properties
const flashSuccess = computed(() => {
    // Check if page.props and page.props.flash exist before accessing .success
    if (page.props && page.props.flash) {
        return page.props.flash.success as string | null;
    }
    return null; // Return null if flash object doesn't exist
});

onMounted(() => {
    // ... rest of your onMounted logic
    const htmlElement = document.documentElement;
    if (htmlElement.classList.contains('dark')) {
        htmlElement.classList.remove('dark');
    }
    htmlElement.classList.add('light');
});
</script>

<template>
    <!-- ... Header ... -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto grid place-items-center gap-8 py-20 md:py-32 lg:max-w-screen-xl">
            <!-- Success Message Display (v-if still works correctly) -->
            <div
                v-if="flashSuccess"
                class="mb-6 w-full max-w-screen-md rounded border border-green-400 bg-green-100 px-4 py-3 text-center text-green-700"
                role="alert"
            >
                <p class="font-bold">¡Éxito!</p>
                <p>{{ flashSuccess }}</p>
            </div>
            <!-- End Success Message Display -->

            <!-- ... Rest of the template ... -->
            <div class="space-y-8 text-center">
                <div class="mx-auto max-w-screen-md text-center text-5xl font-bold md:text-6xl">
                    <h1>¡Operación completada con éxito!</h1>
                </div>
                <div class="mx-auto max-w-screen-md text-center text-lg">
                    <p>Nos pondremos en contacto pronto si es necesario.</p>
                    <a href="/" class="mt-4 inline-block text-blue-600 hover:underline">Volver al inicio</a>
                </div>
            </div>
        </div>
    </section>
</template>

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
