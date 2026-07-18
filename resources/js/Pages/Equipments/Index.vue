<script setup>
import { ref, onMounted, computed } from "vue";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import Filter from "@/Components/Datatable/Filter.vue";
import ShowFilter from "@/Components/Datatable/ShowFilter.vue";
import { formatDateIndo } from "@/lib/utils";

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
import equipmentService from "@/Services/equipmentService";
import Swal from "sweetalert2";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
});

const equipments = ref([]);
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

const fetchEquipments = async () => {
    loading.value = true;

    try {
        const response = await equipmentService.getAll(params.value);

        equipments.value = response.data.data;
        meta.value = response.data.meta;
        links.value = response.data.links;
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

    fetchEquipments();
};

const onSortTable = (field) => {
    if (params.value.field === field) {
        params.value.direction =
            params.value.direction === "asc" ? "desc" : "asc";
    } else {
        params.value.field = field;
        params.value.direction = "asc";
    }

    fetchEquipments();
};

const onDelete = async (id) => {
    try {
        await equipmentService.delete(id);

        await fetchEquipments();
    } catch (error) {
        console.error(error);
    }
};

const user = JSON.parse(localStorage.getItem("user"));

const isAdmin = computed(() => {
    return user?.role === "admin" || user?.role?.name === "admin";
});

onMounted(() => {
    fetchEquipments();
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

                    <Button variant="emerald" size="xl" as-child v-if="isAdmin">
                        <Link :href="route('equipments.create')">
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
                    v-if="equipments.length === 0"
                    :icon="IconPlane"
                    title="Tidak ada Equipment"
                    subtitle="Mulailah dengan menambahkan data Equipment"
                />

                <!-- Table Kategori -->
                <Table v-else class="w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('code')"
                                >
                                    Code
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('name')"
                                >
                                    Nama
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>Kategori</TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('brand')"
                                >
                                    Brand
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('stock')"
                                >
                                    Stock
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>Condition</TableHead>

                            <TableHead>Status</TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('created_at')"
                                >
                                    Dibuat
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead class="text-center"> Aksi </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="(equipment, index) in equipments"
                            :key="equipment.id"
                        >
                            <TableCell>
                                {{ index + 1 }}
                            </TableCell>

                            <TableCell>
                                {{ equipment.code }}
                            </TableCell>

                            <TableCell class="font-medium">
                                {{ equipment.name }}
                            </TableCell>

                            <TableCell>
                                {{ equipment.category?.name ?? "-" }}
                            </TableCell>

                            <TableCell>
                                {{ equipment.brand }}
                            </TableCell>

                            <TableCell>
                                {{ equipment.stock }}
                            </TableCell>

                            <TableCell>
                                {{ equipment.condition }}
                            </TableCell>

                            <TableCell>
                                {{ equipment.status }}
                            </TableCell>

                            <TableCell>
                                {{ formatDateIndo(equipment.created_at) }}
                            </TableCell>

                            <TableCell>
                                <div class="flex items-center gap-x-1">
                                    <Button
                                        variant="blue"
                                        size="sm"
                                        as-child
                                        v-if="isAdmin"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'equipments.edit',
                                                    equipment.id,
                                                )
                                            "
                                        >
                                            <IconPencil class="size-4" />
                                        </Link>
                                    </Button>

                                    <AlertAction
                                        :action="() => onDelete(equipment.id)"
                                    >
                                        <Button
                                            variant="red"
                                            size="sm"
                                            v-if="isAdmin"
                                        >
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
                    dari {{ meta.total ?? 0 }} Equipment
                </p>

                <div class="overflow-x-auto">
                    <PaginationTable :meta="meta" :links="links" />
                </div>
            </CardFooter>
        </Card>
    </div>
</template>
