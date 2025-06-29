<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import PieChart from '@/components/ui/chart/PieChart.vue';

const props = defineProps({
    totalRegistrations: { type: Number, required: true },
    newRegistrations: { type: Number, required: true },
    recurrentRegistrations: { type: Number, required: true },
    pendingRegistrations: { type: Number, required: true },
    pendingScheduledInterviews: { type: Number, required: true },
    scheduledInterviews: { type: Number, required: true },
    girlRegistrations: { type: Number, required: true },
    boyRegistrations: { type: Number, required: true },
});

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Panel', href: '/admin' }];

const blueShades = {
    sky: {
        dark: '#0284c7',
        DEFAULT: '#0ea5e9',
        light: '#38bdf8',
        lighter: '#7dd3fc',
    },
};

const registrationGenderData = computed(() => {
    const data = [props.girlRegistrations, props.boyRegistrations];
    const labels = ['Chicas', 'Muchachos'];
    const colors = [blueShades.sky.DEFAULT, blueShades.sky.lighter];

    return {
        labels: labels,
        datasets: [
            {
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
            },
        ],
        // This is the data for the custom breakdown you wanted to keep
        customData: labels.map((label, index) => ({
            label,
            value: data[index],
            color: colors[index],
        })),
    };
});

// --- RESTORED: Computed property with customData for the breakdown ---
const registrationTypeData = computed(() => {
    const data = [props.newRegistrations, props.recurrentRegistrations];
    const labels = ['Nuevas', 'Recurrentes'];
    const colors = [blueShades.sky.DEFAULT, blueShades.sky.lighter];

    return {
        labels: labels,
        datasets: [
            {
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
            },
        ],
        // This is the data for the custom breakdown you wanted to keep
        customData: labels.map((label, index) => ({
            label,
            value: data[index],
            color: colors[index],
        })),
    };
});

// --- RESTORED: Computed property with customData for the breakdown ---
const registrationStatusData = computed(() => {
    const data = [props.pendingRegistrations, props.pendingScheduledInterviews, props.scheduledInterviews];
    const labels = ['Por Revisar', 'Por Agendar', 'Agendadas'];
    const colors = [blueShades.sky.dark, blueShades.sky.DEFAULT, blueShades.sky.light];

    return {
        labels: labels,
        datasets: [
            {
                data: data,
                backgroundColor: colors,
                borderWidth: 0,
            },
        ],
        // This is the data for the custom breakdown you wanted to keep
        customData: labels.map((label, index) => ({
            label,
            value: data[index],
            color: colors[index],
        })),
    };
});
</script>

<template>
    <Head title="Panel" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
            <Card class="w-full">
                <CardHeader>
                    <CardTitle>Total de Inscripciones</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="text-4xl font-bold">
                        {{ props.totalRegistrations }}
                    </div>
                </CardContent>
            </Card>

            <div class="grid auto-rows-min gap-4 md:grid-cols-2 md:gap-8">
                <!-- Chart 1: Registration Types with Custom Breakdown -->
                <Card>
                    <CardHeader>
                        <CardTitle>Tipos de Inscripción</CardTitle>
                        <CardDescription> Distribución entre nuevas y recurrentes. </CardDescription>
                    </CardHeader>
                    <!-- RESTORED: This is the layout you liked -->
                    <CardContent class="flex flex-col gap-4 sm:flex-row">
                        <div class="h-[200px] w-full flex-1">
                            <PieChart :chart-data="registrationTypeData" />
                        </div>
                        <!-- This is the custom breakdown you wanted to keep -->
                        <div class="flex flex-1 flex-col justify-center gap-4">
                            <div v-for="item in registrationTypeData.customData" :key="item.label" class="flex items-center">
                                <span class="h-3 w-3 rounded-full" :style="{ backgroundColor: item.color }" />
                                <div class="ml-2 flex flex-1 justify-between">
                                    <span class="text-muted-foreground">{{ item.label }}</span>
                                    <span class="font-semibold">{{ item.value }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Chart 2: Registration Status with Custom Breakdown -->
                <Card>
                    <CardHeader>
                        <CardTitle>Estado de los Procesos</CardTitle>
                        <CardDescription> Estado actual de los procesos pendientes. </CardDescription>
                    </CardHeader>
                    <!-- RESTORED: This is the layout you liked -->
                    <CardContent class="flex flex-col gap-4 sm:flex-row">
                        <div class="h-[200px] w-full flex-1">
                            <PieChart :chart-data="registrationStatusData" />
                        </div>
                        <!-- This is the custom breakdown you wanted to keep -->
                        <div class="flex flex-1 flex-col justify-center gap-4">
                            <div v-for="item in registrationStatusData.customData" :key="item.label" class="flex items-center">
                                <span class="h-3 w-3 rounded-full" :style="{ backgroundColor: item.color }" />
                                <div class="ml-2 flex flex-1 justify-between">
                                    <span class="text-muted-foreground">{{ item.label }}</span>
                                    <span class="font-semibold">{{ item.value }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Distribución Chichas/Muchachos</CardTitle>
                        <CardDescription> </CardDescription>
                    </CardHeader>
                    <!-- RESTORED: This is the layout you liked -->
                    <CardContent class="flex flex-col gap-4 sm:flex-row">
                        <div class="h-[200px] w-full flex-1">
                            <PieChart :chart-data="registrationGenderData" />
                        </div>
                        <!-- This is the custom breakdown you wanted to keep -->
                        <div class="flex flex-1 flex-col justify-center gap-4">
                            <div v-for="item in registrationGenderData.customData" :key="item.label" class="flex items-center">
                                <span class="h-3 w-3 rounded-full" :style="{ backgroundColor: item.color }" />
                                <div class="ml-2 flex flex-1 justify-between">
                                    <span class="text-muted-foreground">{{ item.label }}</span>
                                    <span class="font-semibold">{{ item.value }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
