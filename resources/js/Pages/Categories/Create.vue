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

import categoryService from "@/Services/categoryService";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    pageSettings: Object,
    items: Array,
    categories: Array,
});

const loading = ref(false);

const form = ref({
    name: "",
    description: "",
});

const errors = ref({});

const categories = ref([]);

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

const resetForm = () => {
    form.value = {
        name: "",
        description: "",
    };
};

const validateForm = () => {
    errors.value = {};

    if (!form.value.name.trim()) {
        errors.value.name = ["Nama kategori wajib diisi."];
    }

    return Object.keys(errors.value).length === 0;
};

const onHandleSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    loading.value = true;
    errors.value = {};

    try {
        await categoryService.create(form.value);

        router.visit(route("categories.index"));
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
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
                        <Link :href="route('categories.index')">
                            <IconArrowBack class="size-4" />
                            Kembali
                        </Link>
                    </Button>
                </div>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="onHandleSubmit" class="space-y-8">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <Label for="name">Nama Kategori</Label>

                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Contoh: Elektronik"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.name,
                                }"
                            />

                            <InputError :message="errors.name?.[0]" />
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Deskripsi</Label>

                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-md border px-3 py-2"
                                placeholder="Masukkan deskripsi kategori (opsional)"
                            ></textarea>

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
                            <IconCheck class="size-4" />

                            Simpan
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
