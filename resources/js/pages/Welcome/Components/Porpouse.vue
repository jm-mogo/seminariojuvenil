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
        title: 'Fortalecer la vida cristiana',
        description: 'Brindar un espacio sano para crecer espiritualmente.',
    },
    {
        icon: 'lineChart',
        title: 'Desarrollar habilidades',
        description: 'Capacitar y enseñar al joven a enfrentar cada etapa de su vida.',
    },

    {
        icon: 'sparkle',
        title: 'Agradar a Dios',
        description: 'Compartir y divertirnos mientras agradamos a Dios.',
    },
    {
        icon: 'paintbrush',
        title: 'Actividades recreativas',
        description: 'Disfrutar de actividades recreativas y deportivas.',
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
                <h2 class="text-primary mb-2 text-lg tracking-wider"></h2>

                <h2 class="mb-4 text-3xl font-bold md:text-4xl">Sobre el Seminario Juvenil</h2>
                <p class="text-muted-foreground mb-8 text-xl">
                    Es un programa juvenil cristiano exclusivo para adolescentes de 14 - 17 años. Nuestras actividades se llevan a cabo en las
                    instalaciones de la IBBVA, tiene una duración de 8 semanas; de miércoles a viernes. para ayudar al joven a fortalecer su vida como
                    cristiano por medio de la palabra de Dios, buen compañerismo y actividades recreativas.
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
