<script setup>
import { onMounted, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import InputError from "@/Components/InputError.vue";

import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardHeader } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

import { IconArrowBack, IconCheck, IconTool } from "@tabler/icons-vue";

import equipmentService from "@/Services/equipmentService";
import categoryService from "@/Services/categoryService";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
    id: String,
});

const loading = ref(false);

const form = ref({
    category_id: "",
    code: "",
    name: "",
    brand: "",
    stock: 0,
    condition: "good",
    status: "available",
});

const errors = ref({});
const categories = ref([]);

const fetchCategories = async () => {
    const response = await categoryService.getAll({
        per_page: 999,
    });

    categories.value = response.data.data;
};

const fetchEquipment = async () => {
    try {
        const response = await equipmentService.getById(props.id);

        console.log(response);

        const equipment = response.data.data;

        form.value = {
            category_id: equipment.category_id,
            code: equipment.code,
            name: equipment.name,
            brand: equipment.brand,
            stock: equipment.stock,
            condition: equipment.condition,
            status: equipment.status,
        };
    } catch (error) {
        console.error(error);
    }
};

const resetForm = () => {
    form.value = {
        category_id: "",
        code: "",
        name: "",
        brand: "",
        stock: 0,
        condition: "good",
        status: "available",
    };
};

const validateForm = () => {
    errors.value = {};

    if (!form.value.category_id) {
        errors.value.category_id = ["Kategori wajib dipilih."];
    }

    if (!form.value.code) {
        errors.value.code = ["Kode equipment wajib diisi."];
    }

    if (!form.value.name) {
        errors.value.name = ["Nama equipment wajib diisi."];
    }

    if (!form.value.brand) {
        errors.value.brand = ["Brand wajib diisi."];
    }

    if (form.value.stock === "" || form.value.stock === null) {
        errors.value.stock = ["Stock wajib diisi."];
    }

    if (!form.value.condition) {
        errors.value.condition = ["Kondisi wajib dipilih."];
    }

    if (!form.value.status) {
        errors.value.status = ["Status wajib dipilih."];
    }

    return Object.keys(errors.value).length === 0;
};

const onHandleSubmit = async () => {
    if (!validateForm()) return;

    loading.value = true;
    errors.value = {};

    try {
        await equipmentService.update(props.id, form.value);

        router.visit(route("equipments.index"));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    await Promise.all([fetchCategories(), fetchEquipment()]);
});
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
                        :icon="IconTool"
                    />

                    <Button variant="emerald" size="xl" as-child>
                        <Link :href="route('equipments.index')">
                            <IconArrowBack class="size-4" />
                            Kembali
                        </Link>
                    </Button>
                </div>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="onHandleSubmit" class="space-y-8">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- ================= CATEGORY ================= -->
                        <div class="space-y-2">
                            <Label for="category">Kategori</Label>

                            <Select v-model="form.category_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Pilih kategori" />
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>

                            <InputError :message="errors.category_id?.[0]" />
                        </div>

                        <!-- ================= CODE ================= -->

                        <div class="space-y-2">
                            <Label for="code">Kode Equipment</Label>

                            <Input
                                id="code"
                                v-model="form.code"
                                placeholder="Contoh : EQ001"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.code,
                                }"
                            />

                            <InputError :message="errors.code?.[0]" />
                        </div>

                        <!-- ================= NAME ================= -->

                        <div class="space-y-2">
                            <Label for="name">Nama Equipment</Label>

                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Masukkan nama equipment"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.name,
                                }"
                            />

                            <InputError :message="errors.name?.[0]" />
                        </div>

                        <!-- ================= BRAND ================= -->

                        <div class="space-y-2">
                            <Label for="brand">Brand</Label>

                            <Input
                                id="brand"
                                v-model="form.brand"
                                placeholder="Contoh : Logitech"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.brand,
                                }"
                            />

                            <InputError :message="errors.brand?.[0]" />
                        </div>

                        <!-- ================= STOCK ================= -->

                        <div class="space-y-2">
                            <Label for="stock">Stock</Label>

                            <Input
                                id="stock"
                                type="number"
                                min="0"
                                v-model="form.stock"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.stock,
                                }"
                            />

                            <InputError :message="errors.stock?.[0]" />
                        </div>

                        <!-- ================= CONDITION ================= -->

                        <div class="space-y-2">
                            <Label>Kondisi</Label>

                            <Select v-model="form.condition">
                                <SelectTrigger
                                    :class="{
                                        'border-red-500': errors.condition,
                                    }"
                                >
                                    <SelectValue />
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem value="good"> Good </SelectItem>

                                    <SelectItem value="maintenance">
                                        Maintenance
                                    </SelectItem>

                                    <SelectItem value="damaged">
                                        Damaged
                                    </SelectItem>
                                </SelectContent>
                            </Select>

                            <InputError :message="errors.condition?.[0]" />
                        </div>

                        <!-- ================= STATUS ================= -->

                        <div class="space-y-2">
                            <Label>Status</Label>

                            <Select v-model="form.status">
                                <SelectTrigger
                                    :class="{
                                        'border-red-500': errors.status,
                                    }"
                                >
                                    <SelectValue />
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem value="available">
                                        Available
                                    </SelectItem>

                                    <SelectItem value="not_available">
                                        Not Available
                                    </SelectItem>
                                </SelectContent>
                            </Select>

                            <InputError :message="errors.status?.[0]" />
                        </div>
                    </div>

                    <!-- ================= BUTTON ================= -->

                    <div class="flex justify-end gap-3 border-t pt-6">
                        <Button
                            type="button"
                            variant="ghost"
                            size="xl"
                            @click="resetForm"
                        >
                            Reset
                        </Button>

                        <Button
                            type="submit"
                            variant="emerald"
                            size="xl"
                            :disabled="loading"
                        >
                            <IconCheck class="size-4" />

                            Update
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
