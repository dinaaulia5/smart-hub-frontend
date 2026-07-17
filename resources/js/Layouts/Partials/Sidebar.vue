<script setup>
import { ref, onMounted, computed } from "vue";
import authService from "@/Services/authService";
import { router } from "@inertiajs/vue3";
import { IconLogout } from "@tabler/icons-vue";

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";

import { Card, CardContent } from "@/Components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

import {
    IconLayoutDashboard,
    IconMapPin,
    IconPlane,
    IconPlaneDeparture,
    IconTicket,
    IconRobot,
    IconHistory,
} from "@tabler/icons-vue";

import { usePage } from "@inertiajs/vue3";

const page = usePage();

const url = computed(() => page.url);

const user = ref(null);

onMounted(() => {
    const data = localStorage.getItem("user");

    if (data) {
        user.value = JSON.parse(data);
    }
});

const logout = async () => {
    try {
        await authService.logout();
    } catch (error) {
        console.error(error);
    } finally {
        router.visit("/");
    }
};
</script>

<template>
    <nav class="flex flex-1 flex-col gap-y-6 overflow-y-auto scrollbar-none">
        <ApplicationLogo url="#" />

        <Card>
            <CardContent class="flex items-center gap-3 p-3">
                <Avatar>
                    <AvatarImage :src="user?.avatar" />
                    <AvatarFallback>
                        {{ user?.name?.substring(0, 1) }}
                    </AvatarFallback>
                </Avatar>

                <div class="flex flex-col">
                    <span
                        class="line-clamp-1 text-sm font-medium tracking-tight"
                    >
                        {{ user?.name }}
                    </span>

                    <span class="text-xs text-muted-foreground">
                        {{ user?.id }}
                    </span>
                </div>
            </CardContent>
        </Card>

        <ul role="list" class="flex flex-1 flex-col gap-y-2">
            <!-- Dashboard -->
            <div class="px-3 py-2 text-sm font-medium text-muted-foreground">
                General
            </div>

            <NavLink
                title="Dashboard"
                :url="route('dashboard')"
                :active="url.startsWith('/dashboard')"
                :icon="IconLayoutDashboard"
            />

            <!-- Dashboard -->
            <div class="px-3 py-2 text-sm font-medium text-muted-foreground">
                Data Master
            </div>

            <NavLink
                title="Equipment Category"
                :url="route('categories.index')"
                :active="url.startsWith('/categories')"
                :icon="IconLayoutDashboard"
            />
            <div class="mt-auto border-t pt-4">
                <button
                    @click="logout"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950"
                >
                    <IconLogout class="size-5" />
                    Logout
                </button>
            </div>
        </ul>
    </nav>
</template>
