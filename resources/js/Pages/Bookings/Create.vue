<script setup>
import { ref, onMounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";

import bookingService from "@/Services/bookingService";
import roomService from "@/Services/roomService";
import equipmentService from "@/Services/equipmentService";

// UI Components
import Button from "@/Components/ui/button/Button.vue";
import Input from "@/Components/ui/input/Input.vue";
import Label from "@/Components/ui/label/Label.vue";

import Card from "@/Components/ui/card/Card.vue";
import CardHeader from "@/Components/ui/card/CardHeader.vue";
import CardTitle from "@/Components/ui/card/CardTitle.vue";
import CardDescription from "@/Components/ui/card/CardDescription.vue";
import CardContent from "@/Components/ui/card/CardContent.vue";

// Select
import Select from "@/Components/ui/select/Select.vue";
import SelectTrigger from "@/Components/ui/select/SelectTrigger.vue";
import SelectValue from "@/Components/ui/select/SelectValue.vue";
import SelectContent from "@/Components/ui/select/SelectContent.vue";
import SelectItem from "@/Components/ui/select/SelectItem.vue";

// Form
import InputError from "@/Components/InputError.vue";

// Icons
import {
    IconArrowBack,
    IconPlane,
    IconPlus,
    IconTrash,
    IconDeviceFloppy,
} from "@tabler/icons-vue";

defineOptions({
    layout: AppLayout,
});

defineProps({
    pageSettings: Object,
    items: Array,
});

const loading = ref(false);
const errors = ref({});

const rooms = ref([]);
const equipments = ref([]);

const form = ref({
    start_time: "",
    end_time: "",
    items: [
        {
            bookable_type: "",
            bookable_id: "",
            quantity: 1,
        },
    ],
});

/*
|--------------------------------------------------------------------------
| Master Data
|--------------------------------------------------------------------------
*/

const fetchRooms = async () => {
    const response = await roomService.getAll();
    rooms.value = response.data.data;
};

const fetchEquipments = async () => {
    const response = await equipmentService.getAll();
    equipments.value = response.data.data;
};

const fetchMaster = async () => {
    loading.value = true;

    try {
        await Promise.all([fetchRooms(), fetchEquipments()]);
    } catch (error) {
        console.error(error);
    } finally {
        loading.value = false;
    }
};

/*
|--------------------------------------------------------------------------
| Item
|--------------------------------------------------------------------------
*/

const addItem = () => {
    form.value.items.push({
        bookable_type: "",
        bookable_id: "",
        quantity: 1,
    });
};

const removeItem = (index) => {
    form.value.items.splice(index, 1);

    if (form.value.items.length === 0) {
        addItem();
    }
};

/*
|--------------------------------------------------------------------------
| Type Changed
|--------------------------------------------------------------------------
*/

const onChangeType = (item) => {
    item.bookable_id = "";
};

/*
|--------------------------------------------------------------------------
| Options
|--------------------------------------------------------------------------
*/

const getOptions = (type) => {
    switch (type) {
        case "room":
            return rooms.value;

        case "equipment":
            return equipments.value;

        default:
            return [];
    }
};

/*
|--------------------------------------------------------------------------
| Reset
|--------------------------------------------------------------------------
*/

const resetForm = () => {
    form.value = {
        start_time: "",
        end_time: "",
        items: [
            {
                bookable_type: "",
                bookable_id: "",
                quantity: 1,
            },
        ],
    };

    errors.value = {};
};

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const onHandleSubmit = async () => {
    loading.value = true;
    errors.value = {};

    try {
        console.log("PAYLOAD");
        console.log(JSON.stringify(form.value, null, 2));

        await bookingService.create(form.value);

        await Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Booking berhasil dibuat",
            timer: 1500,
            showConfirmButton: false,
        });

        router.visit(route("bookings.index"));
    } catch (error) {
        console.log(error.response?.data);

        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
            return;
        }

        Swal.fire({
            icon: "error",
            title: "Error",
            text: error.response?.data?.message ?? "Terjadi kesalahan.",
        });
    } finally {
        loading.value = false;
    }
};

onMounted(fetchMaster);
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <BreadcrumbHeader :items="items" />

        <Card>
            <CardHeader>
                <div
                    class="flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="pageSettings.title"
                        :sub-title="pageSettings.subtitle"
                        :icon="IconPlane"
                    />

                    <Button variant="emerald" size="xl" as-child>
                        <Link :href="route('bookings.index')">
                            <IconArrowBack class="size-4" />
                            Kembali
                        </Link>
                    </Button>
                </div>
            </CardHeader>

            <CardContent>
                <form class="space-y-6" @submit.prevent="onHandleSubmit">
                    <!-- Booking Time -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Booking Time</CardTitle>
                            <CardDescription>
                                Tentukan waktu penggunaan fasilitas.
                            </CardDescription>
                        </CardHeader>

                        <CardContent
                            class="grid grid-cols-1 gap-4 md:grid-cols-2"
                        >
                            <div class="space-y-2">
                                <Label>Start Time</Label>

                                <Input
                                    v-model="form.start_time"
                                    type="datetime-local"
                                />

                                <InputError :message="errors.start_time?.[0]" />
                            </div>

                            <div class="space-y-2">
                                <Label>End Time</Label>

                                <Input
                                    v-model="form.end_time"
                                    type="datetime-local"
                                />

                                <InputError :message="errors.end_time?.[0]" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Booking Items -->
                    <Card>
                        <CardHeader
                            class="flex flex-row items-center justify-between"
                        >
                            <div>
                                <CardTitle>Booking Items</CardTitle>

                                <CardDescription>
                                    Tambahkan Room atau Equipment yang akan
                                    dibooking.
                                </CardDescription>
                            </div>

                            <Button type="button" @click="addItem">
                                <IconPlus class="mr-2 h-4 w-4" />
                                Tambah Item
                            </Button>
                        </CardHeader>

                        <CardContent>
                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                                class="mb-4 rounded-lg border p-4"
                            >
                                <div class="grid grid-cols-12 gap-4">
                                    <!-- TYPE -->

                                    <div class="col-span-3">
                                        <Label>Type</Label>

                                        <Select
                                            v-model="item.bookable_type"
                                            @update:modelValue="
                                                onChangeType(item)
                                            "
                                        >
                                            <SelectTrigger>
                                                <SelectValue
                                                    placeholder="Pilih Type"
                                                />
                                            </SelectTrigger>

                                            <SelectContent>
                                                <SelectItem value="room">
                                                    Room
                                                </SelectItem>

                                                <SelectItem value="equipment">
                                                    Equipment
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>

                                        <InputError
                                            :message="
                                                errors[
                                                    `items.${index}.bookable_type`
                                                ]?.[0]
                                            "
                                        />
                                    </div>

                                    <!-- ITEM -->

                                    <div class="col-span-5">
                                        <Label>Item</Label>

                                        <Select
                                            v-model="item.bookable_id"
                                            :disabled="!item.bookable_type"
                                        >
                                            <SelectTrigger>
                                                <SelectValue
                                                    placeholder="Pilih Item"
                                                />
                                            </SelectTrigger>

                                            <SelectContent>
                                                <SelectItem
                                                    v-for="option in getOptions(
                                                        item.bookable_type,
                                                    )"
                                                    :key="option.id"
                                                    :value="option.id"
                                                >
                                                    {{ option.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>

                                        <InputError
                                            :message="
                                                errors[
                                                    `items.${index}.bookable_id`
                                                ]?.[0]
                                            "
                                        />
                                    </div>

                                    <!-- QUANTITY -->

                                    <div class="col-span-2">
                                        <Label>Qty</Label>

                                        <Input
                                            v-model.number="item.quantity"
                                            type="number"
                                            min="1"
                                        />

                                        <InputError
                                            :message="
                                                errors[
                                                    `items.${index}.quantity`
                                                ]?.[0]
                                            "
                                        />
                                    </div>

                                    <!-- DELETE -->

                                    <div class="col-span-2 flex items-end">
                                        <Button
                                            v-if="form.items.length > 1"
                                            type="button"
                                            variant="destructive"
                                            class="w-full"
                                            @click="removeItem(index)"
                                        >
                                            <IconTrash class="mr-2 h-4 w-4" />
                                            Hapus
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- ACTION -->

                    <div class="flex justify-end gap-3">
                        <Button
                            type="button"
                            variant="outline"
                            @click="resetForm"
                        >
                            Reset
                        </Button>

                        <Button type="submit" :disabled="loading">
                            <IconDeviceFloppy class="mr-2 h-4 w-4" />
                            Booking
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
