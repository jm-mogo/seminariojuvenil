<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Calendar, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Panel',
        href: '/admin',
        icon: LayoutGrid,
    },
    {
        title: 'Inscripciones',
        href: '/admin/registrations',
        icon: Folder,
    },
    {
        title: 'Entrevistas',
        href: '/admin/appointments',
        icon: Calendar,
    },
];

// const footerNavItems: NavItem[] = [
//     {
//         title: 'jm-mogo',
//         href: 'https://github.com/jm-mogo',
//         icon: Github,
//     },
//     {
//         title: 'j_mogo',
//         href: 'https://www.instagram.com/j_mogo/',
//         icon: InstagramIcon,
//     },
// ];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('admin.dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
