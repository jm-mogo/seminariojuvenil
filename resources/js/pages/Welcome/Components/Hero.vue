<script setup lang="ts">
import { useColorMode } from '@vueuse/core';
const mode = useColorMode();

import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
const handleClick = () => {
    router.visit('/inscription');
};
</script>

<template>
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto grid place-items-center gap-8 py-20 md:py-32 lg:max-w-screen-xl">
            <div class="space-y-8 text-center">
                <div class="mx-auto max-w-screen-md text-center text-5xl font-bold md:text-6xl">
                    <h1>
                        Se parte del <br />
                        <span class="bg-gradient-to-r from-[#0e1d41] to-[#2e3c6e] bg-clip-text text-transparent">Seminario Juvenil </span>
                        <br />
                        de la IBBVA
                    </h1>
                </div>

                <p class="text-muted-foreground mx-auto max-w-screen-sm text-xl">El lugar ideal para crecer.</p>

                <div class="space-y-4 md:space-y-0 md:space-x-4">
                    <Button class="group/arrow w-5/6 font-bold md:w-1/4" @click="() => handleClick()">
                        Inscribirme
                        <ArrowRight class="ml-2 size-5 transition-transform group-hover/arrow:translate-x-1" />
                    </Button>
                </div>
            </div>

            <div class="group relative mt-14">
                <!-- gradient shadow -->
                <div class="bg-primary/50 img-shadow-animation absolute -top-6 right-12 h-12 w-[90%] rounded-full blur-3xl lg:h-[80%]"></div>

                <img
                    class="rouded-lg border-t-primary/30 img-border-animation relative mx-auto flex w-full items-center rounded-lg border border-t-2 leading-none md:w-[1200px]"
                    :src="mode == 'light' ? 'banner.webp' : 'banner.webp'"
                    alt="dashboard using shadcn-vue"
                />

                <!-- gradient effect img -->
                <div
                    class="from-background/0 via-background/50 to-background absolute bottom-0 left-0 h-20 w-full rounded-lg bg-gradient-to-b md:h-28"
                ></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
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
