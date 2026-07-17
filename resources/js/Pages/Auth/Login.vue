<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import AuthLayout from "@/Layouts/AuthLayout.vue";
import authService from "@/Services/authService";

import Card from "@/Components/ui/card/Card.vue";
import CardHeader from "@/Components/ui/card/CardHeader.vue";
import CardTitle from "@/Components/ui/card/CardTitle.vue";
import CardDescription from "@/Components/ui/card/CardDescription.vue";
import CardContent from "@/Components/ui/card/CardContent.vue";

import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import Button from "@/Components/ui/button/Button.vue";

const form = ref({
    email: "",
    password: "",
});

const loading = ref(false);

const login = async () => {
    loading.value = true;

    try {
        await authService.login(form.value);
        Swal.fire({
            icon: "success",
            title: "Login Berhasil",
            timer: 1200,
            showConfirmButton: false,
        });
        router.visit("/dashboard");
    } catch (err) {
        Swal.fire({
            icon: "error",
            title: "Login Gagal",
            text: err.response?.data?.message || "Email atau Password salah.",
        });
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <AuthLayout>
        <Card>
            <CardHeader>
                <CardTitle>Smart Hub Management</CardTitle>
                <CardDescription>
                    Silakan login untuk melanjutkan
                </CardDescription>
            </CardHeader>

            <CardContent>
                <form @submit.prevent="login" class="space-y-4">
                    <div>
                        <Label>Email</Label>
                        <Input
                            v-model="form.email"
                            type="email"
                            placeholder="Masukkan email"
                        />
                    </div>

                    <div>
                        <Label>Password</Label>
                        <Input
                            v-model="form.password"
                            type="password"
                            placeholder="Masukkan password"
                        />
                    </div>

                    <Button type="submit" class="w-full" :disabled="loading">
                        {{ loading ? "Logging in..." : "Login" }}
                    </Button>
                </form>
            </CardContent>
        </Card>
    </AuthLayout>
</template>
