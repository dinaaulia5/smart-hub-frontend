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

import roomService from "@/Services/roomService";
import categoryService from "@/Services/categoryService";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
    categories: Array,
});

const form = ref({
    code: "",
    name: "",
    capacity: "",
    location: "",
    description: "",
    status: "available",
});

const errors = ref({});
const loading = ref(false);

const resetForm = () => {
    form.value = {
        code: "",
        name: "",
        capacity: "",
        location: "",
        description: "",
        status: "available",
    };

    errors.value = {};
};

const fetchCategories = async () => {
    try {
        const response = await categoryService.getAll({
            per_page: 999,
        });

        categories.value = response.data.data;
    } catch (error) {
        console.error(error);
    }
};

const validateForm = () => {
    errors.value = {};

    if (!form.value.code) {
        errors.value.code = ["Kode ruangan wajib diisi."];
    }

    if (!form.value.name) {
        errors.value.name = ["Nama ruangan wajib diisi."];
    }

    if (!form.value.capacity) {
        errors.value.capacity = ["Kapasitas wajib diisi."];
    }

    if (!form.value.location) {
        errors.value.location = ["Lokasi wajib diisi."];
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
        await roomService.create(form.value);

        router.visit(route("rooms.index"));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error(error);
        }
    } finally {
        loading.value = false;
    }
};
onMounted(() => {
    fetchCategories();
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
                        <Link :href="route('rooms.index')">
                            <IconArrowBack class="size-4" />
                            Kembali
                        </Link>
                    </Button>
                </div>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="onHandleSubmit" class="space-y-8">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- ================= CODE ================= -->
                        <div class="space-y-2">
                            <Label for="code">Kode Ruangan</Label>

                            <Input
                                id="code"
                                v-model="form.code"
                                placeholder="Contoh : RM001"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.code,
                                }"
                            />

                            <InputError :message="errors.code?.[0]" />
                        </div>

                        <!-- ================= NAME ================= -->
                        <div class="space-y-2">
                            <Label for="name">Nama Ruangan</Label>

                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Masukkan nama ruangan"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.name,
                                }"
                            />

                            <InputError :message="errors.name?.[0]" />
                        </div>

                        <!-- ================= CAPACITY ================= -->
                        <div class="space-y-2">
                            <Label for="capacity">Kapasitas</Label>

                            <Input
                                id="capacity"
                                type="number"
                                min="1"
                                v-model="form.capacity"
                                placeholder="Contoh : 20"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.capacity,
                                }"
                            />

                            <InputError :message="errors.capacity?.[0]" />
                        </div>

                        <!-- ================= LOCATION ================= -->
                        <div class="space-y-2">
                            <Label for="location">Lokasi</Label>

                            <Input
                                id="location"
                                v-model="form.location"
                                placeholder="Contoh : Lantai 3 Gedung A"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.location,
                                }"
                            />

                            <InputError :message="errors.location?.[0]" />
                        </div>

                        <!-- ================= STATUS ================= -->
                        <div class="space-y-2">
                            <Label>Status</Label>

                            <Select v-model="form.status">
                                <SelectTrigger
                                    class="w-full"
                                    :class="{
                                        'border-red-500': errors.status,
                                    }"
                                >
                                    <SelectValue placeholder="Pilih Status" />
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem value="available">
                                        Tersedia
                                    </SelectItem>

                                    <SelectItem value="not_available">
                                        Tidak Tersedia
                                    </SelectItem>
                                </SelectContent>
                            </Select>

                            <InputError :message="errors.status?.[0]" />
                        </div>

                        <!-- ================= DESCRIPTION ================= -->
                        <div class="space-y-2 lg:col-span-2">
                            <Label for="description">Deskripsi</Label>

                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="5"
                                placeholder="Masukkan deskripsi ruangan (opsional)"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            />

                            <InputError :message="errors.description?.[0]" />
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
                            <IconCheck class="mr-2 size-4" />

                            {{ loading ? "Menyimpan..." : "Simpan" }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
