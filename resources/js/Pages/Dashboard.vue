<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Dashboard" />
</template>
