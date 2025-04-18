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
                    <h1>Antes de continuar</h1>
                </div>

                <Card class="mx-auto max-w-screen-sm">
                    <CardHeader>
                        <CardTitle class="text-center text-2xl">Requisitos</CardTitle>
                    </CardHeader>
                    <CardContent class="p-6 text-left text-lg font-medium">
                        <ul class="list-disc space-y-3 pl-5">
                            <li>Foto personal reciente (tipo carnet, formato JPG o PNG)</li>
                            <li>Foto legible de tu cédula de identidad (formato JPG o PDF)</li>
                            <li>Foto legible de cédula de identidad de representante (formato JPG o PDF)</li>
                            <li>Carta de recomendación de tu pastor (solo si vienes de otra iglesia)</li>
                            <li>Testimonio de salvación (escrito en Word o PDF)</li>
                            <li>Propósito por el cual deseas ingresar al Seminario Juvenil (Word o PDF)</li>
                        </ul>
                    </CardContent>
                </Card>

                <p class="mx-auto max-w-screen-sm text-xl font-bold">
                    La inscripción no estará completa hasta haber asistido a la entrevista presencial, leído el reglamento junto al representante y
                    completado los pasos en la oficina.
                </p>

                <div class="space-y-4 md:space-y-0 md:space-x-4">
                    <!-- Inertia Link for "Primera vez" -->
                    <Link href="/inscription/new" class="inline-block w-5/6 md:w-2/5">
                        <Button as-child class="group/arrow w-full font-bold">
                            <span>
                                Primera vez
                                <ArrowRight class="ml-2 inline-block size-5 transition-transform group-hover/arrow:translate-x-1" />
                            </span>
                        </Button>
                    </Link>

                    <!-- Inertia Link for "Reinscripción" -->
                    <Link href="/inscription/old" class="inline-block w-5/6 md:w-2/5">
                        <Button as-child class="group/arrow w-full font-bold">
                            <span>
                                Reinscripción
                                <ArrowRight class="ml-2 inline-block size-5 transition-transform group-hover/arrow:translate-x-1" />
                            </span>
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'; // Import Card components
import { Link } from '@inertiajs/vue3'; // Import Inertia Link
import { ArrowRight } from 'lucide-vue-next';
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
