<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
</script>

<template>
    <footer id="footer" class="container mx-auto py-24 pb-16 sm:py-32 sm:pb-24">
        <div class="bg-muted/50 dark:bg-card rounded-2xl border p-10">
            <div class="grid grid-cols-2 gap-x-12 gap-y-8 md:grid-cols-4 xl:grid-cols-6">
                <div class="col-span-full xl:col-span-2">
                    <a href="#" class="flex items-center gap-2 font-bold">
                        <img src="logo.png" alt="" class="h-12" />
                        <h3 class="text-2xl">Seminario Juvenil</h3>
                    </a>
                </div>
                <div class="flex flex-col gap-2"></div>
                <div class="flex flex-col gap-2"></div>

                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-bold">Contacto</h3>
                    <div>
                        <a href="#" class="opacity-60 hover:opacity-100"> Whatsapp </a>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-bold">Redes sociales</h3>
                    <div>
                        <a href="#" class="opacity-60 hover:opacity-100"> Instagram </a>
                    </div>

                    <div>
                        <a href="#" class="opacity-60 hover:opacity-100"> Facebook </a>
                    </div>
                </div>
            </div>

            <Separator class="my-6" />
            <section className="">
                <h3 class="">
                    &copy; 2025 Seminario Juvenil <br />
                    Desarrollado por
                    <a target="_blank" href="https://github.com/jm-mogo" className="text-primary transition-all border-primary hover:border-b-2">
                        José Mogollón
                    </a>
                </h3>
            </section>
        </div>
    </footer>
</template>
