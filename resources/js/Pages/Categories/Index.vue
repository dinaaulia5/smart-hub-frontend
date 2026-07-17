<script setup>
import { reactive, ref, computed, watch, onMounted } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";

import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/Components/ui/card";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import {
    IconArrowsDownUp,
    IconPencil,
    IconPlane,
    IconPlus,
    IconTrash,
} from "@tabler/icons-vue";
import EmptyState from "@/Components/EmptyState.vue";
import { Tab } from "@headlessui/vue";

import { deleteAction } from "@/lib/utils";
import AlertAction from "@/Components/AlertAction.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";
import categoryService from "@/Services/categoryService";
import Button from "@/Components/ui/button/Button.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
});

const categories = ref([]);
const loading = ref(false);

onMounted(async () => {
    loading.value = true;

    try {
        const response = await categoryService.getAll();

        categories.value = response.data.data;
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div class="flex flex-col w-full gap-y-6 pb-32">
        <Head title="Category" />
        <!-- breadcrumb -->
        <BreadcrumbHeader :items="items" />

        <!-- banner -->
        <Banner
            :title="pageSettings.banner.title"
            :sub-title="pageSettings.banner.subtitle"
        />

        <!-- Table -->
        <Card>
            <!-- header -->
            <CardHeader class="p-0">
                <div
                    class="flex flex-col items-start justify-between p-4 gap-y-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconPlane"
                    />

                    <Button variant="emerald" size="xl" as-child>
                        <Link :href="route('categories.create')">
                            <IconPlus class="size-4" />
                            Tambah
                        </Link>
                    </Button>
                </div>

                <Filter
                    :params="params"
                    :state="state"
                    @update:params="updateParams"
                />
                <ShowFilter :params="params" />
            </CardHeader>

            <CardContent
                class="p-0 [&_td]:whitespace-nowrap [&_td]:px-6 [&_th]:px-6"
            >
                <EmptyState
                    v-if="categories.length === 0"
                    :icon="IconPlane"
                    title="Tidak ada Kategori"
                    subtitle="Mulailah dengan menambahkan data Kategori"
                />

                <!-- table Kategori -->
                <!-- Table Kategori -->
                <Table v-else class="w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    class="inline-flex group"
                                    @click="onSortTable('name')"
                                >
                                    Nama Kategori
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    class="inline-flex group"
                                    @click="onSortTable('description')"
                                >
                                    Deskripsi
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    class="inline-flex group"
                                    @click="onSortTable('created_at')"
                                >
                                    Dibuat
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead class="text-center">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="(category, index) in categories"
                            :key="category.id"
                        >
                            <TableCell>
                                {{ index + 1 }}
                            </TableCell>

                            <TableCell class="font-medium">
                                {{ category.name }}
                            </TableCell>

                            <TableCell>
                                {{ category.description }}
                            </TableCell>

                            <TableCell>
                                {{ category.created_at }}
                            </TableCell>

                            <TableCell>
                                <div class="flex items-center gap-x-1">
                                    <Button variant="blue" size="sm" as-child>
                                        <Link
                                            :href="
                                                route(
                                                    'categories.edit',
                                                    category.id,
                                                )
                                            "
                                        >
                                            <IconPencil class="size-4" />
                                        </Link>
                                    </Button>

                                    <AlertAction
                                        :action="
                                            () =>
                                                deleteAction(
                                                    route(
                                                        'categories.destroy',
                                                        category.id,
                                                    ),
                                                )
                                        "
                                    >
                                        <Button variant="red" size="sm">
                                            <IconTrash class="size-4" />
                                        </Button>
                                    </AlertAction>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>

        <Create
            v-model:open="openModal"
            :mode="modalMode"
            :category="selectedAirport"
        />
    </div>
</template>
