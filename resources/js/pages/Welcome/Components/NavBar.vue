<template>
    <header
        :class="{
            'shadow-light': mode === 'light',
            'shadow-dark': mode === 'dark',
            'bg-card sticky top-5 z-40 mx-auto mt-4 flex w-[90%] items-center justify-between rounded-2xl border p-2 shadow-md md:w-[70%] lg:w-[75%] lg:max-w-screen-xl': true,
        }"
    >
        <a href="/" class="flex items-center gap-2 text-lg font-bold">
            <img src="logo.jpg" alt="" class="h-12" />
            Seminario Juvenil</a
        >
        <!-- Mobile -->
        <div class="flex items-center lg:hidden">
            <Sheet v-model:open="isOpen">
                <SheetTrigger as-child>
                    <Menu @click="isOpen = true" class="cursor-pointer" />
                </SheetTrigger>

                <SheetContent side="left" class="bg-card flex flex-col justify-between rounded-tr-2xl rounded-br-2xl">
                    <div>
                        <SheetHeader class="mb-4 ml-4">
                            <SheetTitle class="flex items-center">
                                <a href="/" class="flex items-center">
                                    <ChevronsDown
                                        class="from-primary/70 via-primary to-primary/70 mr-2 size-9 rounded-lg border bg-gradient-to-tr text-white"
                                    />
                                    ShadcnVue
                                </a>
                            </SheetTitle>
                        </SheetHeader>

                        <div class="flex flex-col gap-2">
                            <Button v-for="{ href, label } in routeList" :key="label" as-child variant="ghost" class="justify-start text-base">
                                <a @click="isOpen = false" :href="href">
                                    {{ label }}
                                </a>
                            </Button>
                        </div>
                    </div>

                    <SheetFooter class="flex-col items-start justify-start sm:flex-col">
                        <Separator class="mb-2" />
                    </SheetFooter>
                </SheetContent>
            </Sheet>
        </div>

        <!-- Desktop -->
        <NavigationMenu class="hidden lg:flex">
            <NavigationMenuList>
                <NavigationMenuItem class="flex items-center justify-between">
                    <NavigationMenuLink asChild class="flex items-center">
                        <Button v-for="{ href, label } in routeList" :key="label" as-child variant="ghost" class="justify-center text-base">
                            <a :href="href">
                                {{ label }}
                            </a>
                        </Button>
                    </NavigationMenuLink>
                </NavigationMenuItem>
            </NavigationMenuList>
        </NavigationMenu>
    </header>
</template>

<script lang="ts" setup>
import { ref } from 'vue';

import { useColorMode } from '@vueuse/core';
const mode = useColorMode();
mode.value = 'light';

import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';

import { ChevronsDown, Menu } from 'lucide-vue-next';

interface RouteProps {
    href: string;
    label: string;
}

const routeList: RouteProps[] = [
    {
        href: '#testimonials',
        label: 'Testimonios',
    },
    {
        href: '#team',
        label: 'Team',
    },
    {
        href: '#contact',
        label: 'Contact',
    },
];

const isOpen = ref<boolean>(false);
</script>

<style scoped>
.shadow-light {
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.085);
}

.shadow-dark {
    box-shadow: inset 0 0 5px rgba(255, 255, 255, 0.141);
}
</style>
