<script setup>
import { ref, onMounted } from "vue";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";
import InputError from "@/Components/InputError.vue";

import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardHeader } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";

import { IconArrowBack, IconCheck, IconCategory } from "@tabler/icons-vue";

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
    name: "",
    description: "",
});

const errors = ref({});

const fetchCategory = async () => {
    try {
        const response = await categoryService.getById(props.id);

        form.value = {
            name: response.data.data.name ?? "",
            description: response.data.data.description ?? "",
        };
    } catch (error) {
        console.error(error);
    }
};

const validateForm = () => {
    errors.value = {};

    if (!form.value.name.trim()) {
        errors.value.name = ["Nama kategori wajib diisi."];
    }

    return Object.keys(errors.value).length === 0;
};

const resetForm = async () => {
    await fetchCategory();
};

const onHandleSubmit = async () => {
    if (!validateForm()) return;

    loading.value = true;
    errors.value = {};

    try {
        await categoryService.update(props.id, form.value);

        router.visit(route("categories.index"));
    } catch (error) {
        console.error(error);

        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchCategory();
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
                        :icon="IconCategory"
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
                            <Label for="name"> Nama Kategori </Label>

                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Contoh : Elektronik"
                                :class="{
                                    'border-red-500 focus-visible:ring-red-500':
                                        errors.name,
                                }"
                            />

                            <InputError :message="errors.name?.[0]" />
                        </div>

                        <div class="space-y-2">
                            <Label for="description"> Deskripsi </Label>

                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="5"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                placeholder="Masukkan deskripsi kategori..."
                            ></textarea>

                            <InputError :message="errors.description?.[0]" />
                        </div>
                    </div>

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

                            {{ loading ? "Menyimpan..." : "Update" }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
