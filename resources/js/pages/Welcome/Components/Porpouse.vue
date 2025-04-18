<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

import { Blocks, LineChart, Paintbrush, Sparkle, Tag, Wallet } from 'lucide-vue-next';

interface BenefitsProps {
    icon: string;
    title: string;
    description: string;
}

const benefitList: BenefitsProps[] = [
    {
        icon: 'blocks',
        title: 'Espacio para crecer',
        description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. A odio velit cum aliquam. Natus consectetur dolores.',
    },
    {
        icon: 'lineChart',
        title: 'Capacitar - Enseñar',
        description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. A odio velit cum aliquam, natus consectetur.',
    },
    {
        icon: 'wallet',
        title: 'Compartir',
        description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus consectetur. A odio velit cum aliquam',
    },
    {
        icon: 'sparkle',
        title: 'Test Marketing Ideas',
        description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. A odio velit cum aliquam. Natus consectetur dolores.',
    },
];

const iconMap: Record<string, typeof Sparkle | typeof Tag | typeof Paintbrush | typeof Blocks | typeof LineChart | typeof Wallet> = {
    sparkle: Sparkle,
    tag: Tag,
    paintbrush: Paintbrush,
    blocks: Blocks,
    lineChart: LineChart,
    wallet: Wallet,
};
</script>

<template>
    <section id="benefits" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid place-items-center lg:grid-cols-2 lg:gap-24">
            <div>
                <h2 class="text-primary mb-2 text-lg tracking-wider">Propósito</h2>

                <h2 class="mb-4 text-3xl font-bold md:text-4xl">Your Shortcut to Success</h2>
                <p class="text-muted-foreground mb-8 text-xl">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Non ducimus reprehenderit architecto rerum similique facere odit
                    deleniti necessitatibus quo quae.
                </p>
            </div>

            <div class="grid w-full gap-4 lg:grid-cols-2">
                <Card
                    v-for="({ icon, title, description }, index) in benefitList"
                    :key="title"
                    class="bg-muted/50 dark:bg-card hover:bg-background dark:hover:bg-background group/number transition-all delay-75"
                >
                    <CardHeader>
                        <div class="flex justify-between">
                            <component class="text-primary mb-6 size-8" :is="iconMap[icon]" />

                            <span
                                class="text-muted-foreground/15 group-hover/number:text-muted-foreground/30 text-5xl font-medium transition-all delay-75"
                                >0{{ index + 1 }}</span
                            >
                        </div>

                        <CardTitle>{{ title }}</CardTitle>
                    </CardHeader>

                    <CardContent class="text-muted-foreground">
                        {{ description }}
                    </CardContent>
                </Card>
            </div>
        </div>
    </section>
</template>
