<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";

import { ref, onMounted, computed } from "vue";
import dashboardService from "@/Services/dashboardService";

import {
    IconUsers,
    IconCategory,
    IconPackage,
    IconDoor,
    IconCalendarEvent,
    IconClockHour4,
    IconCircleCheck,
    IconCircleX,
    IconChecklist,
    IconCalendarStats,
} from "@tabler/icons-vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import Banner from "@/Components/Banner.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
});

const dashboard = ref({});

const getDashboard = async () => {
    try {
        const response = await dashboardService.getDashboard();

        console.log(response.data);

        dashboard.value = response.data.data;
    } catch (error) {
        console.error(error);
    }
};

const user = JSON.parse(localStorage.getItem("user"));

const isAdmin = computed(() => {
    return user?.role === "admin" || user?.role?.name === "admin";
});
const isUser = computed(() => {
    return user?.role === "user" || user?.role?.name === "user";
});
onMounted(() => {
    getDashboard();

    const token = localStorage.getItem("token");

    if (!token) {
        router.visit("/login");
    }
});
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <BreadcrumbHeader :items="items" />

        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <div class="space-y-8">
            <!-- Master Data -->
            <div v-if="isAdmin">
                <h2 class="mb-4 text-xl font-semibold">Master Data</h2>

                <div
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4"
                >
                    <!-- Users -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Total Users
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.total_users }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-blue-100 p-3 text-blue-600"
                            >
                                <IconUsers class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Category -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Categories
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.total_categories }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-emerald-100 p-3 text-emerald-600"
                            >
                                <IconCategory class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Equipment -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Equipments
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.total_equipments }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-orange-100 p-3 text-orange-600"
                            >
                                <IconPackage class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Room -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Rooms
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.total_rooms }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-violet-100 p-3 text-violet-600"
                            >
                                <IconDoor class="size-8" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Booking -->
            <div>
                {{ isAdmin ? "Booking Statistics" : "My Booking Statistics" }}

                <div
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4"
                >
                    <!-- Total Booking -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Total Bookings
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.total_bookings }}
                                </h2>
                            </div>

                            <div class="rounded-xl bg-sky-100 p-3 text-sky-600">
                                <IconCalendarEvent class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Pending -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Pending
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.pending_bookings }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-yellow-100 p-3 text-yellow-600"
                            >
                                <IconClockHour4 class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Completed -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Completed
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.completed_bookings }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-green-100 p-3 text-green-600"
                            >
                                <IconCircleCheck class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Cancelled -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Cancelled
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.cancelled_bookings }}
                                </h2>
                            </div>

                            <div class="rounded-xl bg-red-100 p-3 text-red-600">
                                <IconCircleX class="size-8" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Check In -->
            <div>
                <h2 class="mb-4 text-xl font-semibold">Check In Statistics</h2>

                <div
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3"
                >
                    <!-- Available Equipment -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Available Equipments
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.available_equipments }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-indigo-100 p-3 text-indigo-600"
                            >
                                <IconBox class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Total Check In -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Total Check In
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.total_checkins }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-cyan-100 p-3 text-cyan-600"
                            >
                                <IconChecklist class="size-8" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Today Check In -->
                    <Card
                        class="rounded-xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900"
                    >
                        <CardContent
                            class="flex items-center justify-between p-6"
                        >
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Today Check In
                                </p>

                                <h2 class="mt-2 text-3xl font-bold">
                                    {{ dashboard.today_checkins }}
                                </h2>
                            </div>

                            <div
                                class="rounded-xl bg-pink-100 p-3 text-pink-600"
                            >
                                <IconChecklist class="size-8" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>
