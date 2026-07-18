<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";

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

const meta = ref({
    links: [],
});

const links = ref({
    first: null,
    last: null,
    prev: null,
    next: null,
});

const state = ref({
    search: "",
});

const params = ref({
    search: "",
    field: "created_at",
    direction: "desc",
    page: 1,
    per_page: 10,
});

const fetchCategories = async () => {
    loading.value = true;

    try {
        const response = await categoryService.getAll(params.value);

        categories.value = response.data.data;
        meta.value = response.data.meta;
        links.value = response.data.links;

        console.log("META :", meta.value);
        console.log("LINKS :", links.value);
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

const updateParams = (newParams) => {
    params.value = {
        ...params.value,
        ...newParams,
        page: 1,
    };

    fetchCategories();
};

const onSortTable = (field) => {
    if (params.value.field === field) {
        params.value.direction =
            params.value.direction === "asc" ? "desc" : "asc";
    } else {
        params.value.field = field;
        params.value.direction = "asc";
    }

    fetchCategories();
};

const onPageChange = (page) => {
    params.value.page = page;

    fetchCategories();
};

onMounted(() => {
    fetchCategories();
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
                                                        'categories.delete',
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

            <CardFooter
                class="flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row"
            >
                <p class="text-sm text-muted-foreground">
                    Menampilkan
                    <span class="font-medium text-emerald-600">
                        {{ meta.from ?? 0 }}
                    </span>
                    dari {{ meta.total ?? 0 }} Kategori
                </p>

                <div class="overflow-x-auto">
                    <PaginationTable :meta="meta" :links="links" />
                </div>
            </CardFooter>
        </Card>
    </div>
</template>
