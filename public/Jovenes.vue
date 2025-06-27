<script setup lang="ts">
import { Card, CardContent, CardHeader } from '@/components/ui/card';

interface FeaturesProps {
    imagePathBefore: string;
    imagePathAfter: string;
    name: string;
}

const featureList: FeaturesProps[] = [
    {
        imagePathBefore: 'edward_before.jpg',
        imagePathAfter: 'edward_after.jpg',
        name: 'Edward Tarache',
    },
    {
        imagePathBefore: 'kamila_before.jpg',
        imagePathAfter: 'kamila_after.jpg',
        name: 'Kamila Blanco',
    },
    {
        imagePathBefore: 'jose_before.jpg',
        imagePathAfter: 'jose_after.jpg',
        name: 'Jose Angel',
    },
];

// const iconMap: Record<
//     string,
//     | typeof TabletSmartphone
//     | typeof BadgeCheck
//     | typeof Goal
//     | typeof PictureInPicture
//     | typeof Paintbrush
//     | typeof MousePointerClick
//     | typeof MessageCircle
//     | typeof Newspaper
// > = {
//     tabletSmartphone: TabletSmartphone,
//     badgeCheck: BadgeCheck,
//     goal: Goal,
//     pictureInPicture: PictureInPicture,
//     paintbrush: Paintbrush,
//     mousePointerClick: MousePointerClick,
//     messageCircle: MessageCircle,
//     newspaper: Newspaper,
// };
</script>

<template>
    <section id="features" class="container mx-auto py-24 sm:py-32">
        <!-- <h2 class="text-primary mb-2 text-center text-lg tracking-wider">Features</h2> -->

        <h2 class="mb-4 text-center text-3xl font-bold md:text-4xl">Un antes y un después</h2>

        <h3 class="text-muted-foreground mx-auto mb-8 text-center text-xl md:w-1/2">
            Muchos jóvenes nos han dicho: “no soy el mismo desde que fui al seminario.” Y es que, cuando Cristo obra, hay una línea que divide el
            antes y el después. Este programa ha sido usado por Dios para levantar siervos, restaurar vidas, confirmar llamados y avivar corazones.
        </h3>

        <div class="flex flex-wrap justify-center">
            <div v-for="{ name, imagePathBefore, imagePathAfter } in featureList" :key="name">
                <Card class="bg-background h-full w-106 border-0 shadow-none">
                    <CardHeader class="flex items-center justify-center">
                        <img :src="imagePathBefore" alt="" class="mb-4 h-72 w-48 rounded-lg object-cover object-top shadow-lg" />
                        <img :src="imagePathAfter" alt="" class="mb-4 h-72 w-48 rounded-lg object-cover object-top shadow-lg" />
                    </CardHeader>

                    <CardContent class="text-muted-foreground text-center text-xl"> {{ name }} </CardContent>
                </Card>
            </div>
        </div>
    </section>
</template>

<style lang="less" scoped></style>
