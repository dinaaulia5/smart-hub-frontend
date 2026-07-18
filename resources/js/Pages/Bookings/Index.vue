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
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from "@/Components/ui/dropdown-menu";

import {
    IconDotsVertical,
    IconEye,
    IconPencil,
    IconLogin2,
    IconCircleX,
    IconTrash,
    IconArrowsDownUp,
    IconPlane,
    IconPlus,
} from "@tabler/icons-vue";

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

import EmptyState from "@/Components/EmptyState.vue";
import { Tab } from "@headlessui/vue";

import { deleteAction } from "@/lib/utils";
import AlertAction from "@/Components/AlertAction.vue";
import PaginationTable from "@/Components/Datatable/PaginationTable.vue";
import categoryService from "@/Services/categoryService";
import Button from "@/Components/ui/button/Button.vue";
import bookingService from "@/Services/bookingService";
import CheckInDialog from "./Components/CheckInDialog.vue";
import CancelDialog from "./Components/CancelDialog.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
});

const bookings = ref([]);
const loading = ref(false);
const openCancel = ref(false);
const selectedBooking = ref(null);
const openCheckIn = ref(false);

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

const fetchbookings = async () => {
    loading.value = true;

    try {
        const response = await bookingService.getAll(params.value);

        bookings.value = response.data.data;
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

    fetchbookings();
};

const onSortTable = (field) => {
    if (params.value.field === field) {
        params.value.direction =
            params.value.direction === "asc" ? "desc" : "asc";
    } else {
        params.value.field = field;
        params.value.direction = "asc";
    }

    fetchbookings();
};

const handleCheckInSuccess = () => {
    fetchbookings();
};

const openCancelDialog = async (booking) => {
    try {
        const response = await bookingService.getById(booking.id);

        selectedBooking.value = response.data.data;

        openCancel.value = true;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    fetchbookings();
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
                        <Link :href="route('bookings.create')">
                            <IconPlus class="size-4" />
                            Booking
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
                    v-if="bookings.length === 0"
                    :icon="IconPlane"
                    title="Belum ada Booking"
                    subtitle="Mulailah dengan menambahkan booking baru."
                />

                <!-- Table Kategori -->
                <Table v-else class="w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('booking_code')"
                                >
                                    Booking Code
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('user.name')"
                                >
                                    User
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead> Booking Items </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('start_time')"
                                >
                                    Start Time
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>
                                <Button
                                    variant="ghost"
                                    @click="onSortTable('end_time')"
                                >
                                    End Time
                                    <IconArrowsDownUp class="ml-2 size-4" />
                                </Button>
                            </TableHead>

                            <TableHead>Status</TableHead>

                            <TableHead class="text-center"> Action </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="(booking, index) in bookings"
                            :key="booking.id"
                        >
                            <TableCell>
                                {{ index + 1 }}
                            </TableCell>

                            <TableCell>
                                {{ booking.booking_code }}
                            </TableCell>

                            <TableCell>
                                {{ booking.user?.name }}
                            </TableCell>

                            <TableCell>
                                <div class="space-y-1">
                                    <div
                                        v-for="item in booking.items"
                                        :key="item.id"
                                        class="flex items-center gap-2"
                                    >
                                        <Badge
                                            :variant="
                                                item.bookable_type === 'room'
                                                    ? 'default'
                                                    : 'secondary'
                                            "
                                        >
                                            {{ item.bookable_type }}
                                        </Badge>

                                        <span>
                                            {{ item.bookable?.name }}
                                        </span>

                                        <span class="text-muted-foreground">
                                            × {{ item.quantity }}
                                        </span>
                                    </div>
                                </div>
                            </TableCell>

                            <TableCell>
                                {{ booking.start_time }}
                            </TableCell>

                            <TableCell>
                                {{ booking.end_time }}
                            </TableCell>

                            <TableCell>
                                <Badge>
                                    {{ booking.status }}
                                </Badge>
                            </TableCell>

                            <TableCell>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" size="icon">
                                            <IconDotsVertical class="size-4" />
                                        </Button>
                                    </DropdownMenuTrigger>

                                    <DropdownMenuContent
                                        align="end"
                                        class="w-48"
                                    >
                                        <DropdownMenuLabel>
                                            Actions
                                        </DropdownMenuLabel>

                                        <DropdownMenuSeparator />

                                        <DropdownMenuItem as-child>
                                            <Link
                                                :href="
                                                    route(
                                                        'bookings.show',
                                                        booking.id,
                                                    )
                                                "
                                            >
                                                <IconEye class="mr-2 size-4" />
                                                Detail
                                            </Link>
                                        </DropdownMenuItem>

                                        <DropdownMenuItem as-child>
                                            <Link
                                                :href="
                                                    route(
                                                        'bookings.edit',
                                                        booking.id,
                                                    )
                                                "
                                            >
                                                <IconPencil
                                                    class="mr-2 size-4"
                                                />
                                                Edit
                                            </Link>
                                        </DropdownMenuItem>

                                        <DropdownMenuSeparator />

                                        <DropdownMenuItem
                                            @click="openCheckInDialog(booking)"
                                        >
                                            <IconLogin2 class="mr-2 size-4" />
                                            Check In
                                        </DropdownMenuItem>

                                        <DropdownMenuItem
                                            @click="openCancelDialog(booking)"
                                        >
                                            <IconCircleX class="mr-2 size-4" />
                                            Cancel Booking
                                        </DropdownMenuItem>

                                        <DropdownMenuSeparator />

                                        <DropdownMenuItem
                                            class="text-red-600 focus:text-red-600"
                                            @click="
                                                deleteAction(
                                                    route(
                                                        'bookings.destroy',
                                                        booking.id,
                                                    ),
                                                )
                                            "
                                        >
                                            <IconTrash class="mr-2 size-4" />
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
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

        <CheckInDialog
            v-model:open="openCheckIn"
            :booking="selectedBooking"
            @success="handleCheckInSuccess"
        />

        <CancelDialog
            v-model:open="openCancel"
            :booking="selectedBooking"
            @success="fetchbookings"
        />
    </div>
</template>
