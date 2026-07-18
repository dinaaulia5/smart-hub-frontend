<script setup>
import { ref } from "vue";
import { Head, router, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";

import authService from "@/Services/authService";

import Card from "@/Components/ui/card/Card.vue";
import CardContent from "@/Components/ui/card/CardContent.vue";

import Label from "@/Components/ui/label/Label.vue";
import Input from "@/Components/ui/input/Input.vue";
import Button from "@/Components/ui/button/Button.vue";

const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const loading = ref(false);

const register = async () => {
    loading.value = true;

    try {
        await authService.register(form.value);

        Swal.fire({
            icon: "success",
            title: "Registrasi Berhasil",
            text: "Silakan login menggunakan akun yang baru dibuat.",
            timer: 1800,
            showConfirmButton: false,
        });

        router.visit("/login");
    } catch (err) {
        Swal.fire({
            icon: "error",
            title: "Registrasi Gagal",
            text:
                err.response?.data?.message ||
                "Terjadi kesalahan saat registrasi.",
        });
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Register" />

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-50 via-white to-cyan-100 p-6"
    >
        <Card
            class="w-full max-w-5xl overflow-hidden rounded-3xl shadow-2xl border-0"
        >
            <CardContent class="grid md:grid-cols-2 p-0">
                <!-- LEFT -->
                <div
                    class="hidden md:flex flex-col justify-center items-center bg-gradient-to-br from-emerald-600 to-teal-700 text-white p-10"
                >
                    <img src="/images/logo.png" class="w-36 mb-8" alt="Logo" />

                    <h1 class="text-4xl font-bold">Smart Hub</h1>

                    <p class="mt-2 opacity-90">Management System</p>

                    <div class="mt-10 space-y-4 text-left">
                        <div>✅ Room Management</div>
                        <div>✅ Equipment Management</div>
                        <div>✅ Booking System</div>
                        <div>✅ Check In</div>
                        <div>✅ Dashboard Analytics</div>
                    </div>
                </div>

                <!-- RIGHT -->
                <form
                    class="p-10 flex flex-col justify-center"
                    @submit.prevent="register"
                >
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold">Create Account 🚀</h2>

                        <p class="text-slate-500 mt-2">
                            Daftarkan akun untuk menggunakan Smart Hub
                            Management System.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <Label>Nama Lengkap</Label>

                            <Input
                                v-model="form.name"
                                type="text"
                                placeholder="Masukkan nama lengkap"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <Label>Email</Label>

                            <Input
                                v-model="form.email"
                                type="email"
                                placeholder="Masukkan email"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <Label>Password</Label>

                            <Input
                                v-model="form.password"
                                type="password"
                                placeholder="Masukkan password"
                                class="mt-2"
                            />
                        </div>

                        <div>
                            <Label>Konfirmasi Password</Label>

                            <Input
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="Ulangi password"
                                class="mt-2"
                            />
                        </div>

                        <Button
                            type="submit"
                            variant="emerald"
                            class="w-full h-11"
                            :disabled="loading"
                        >
                            {{ loading ? "Loading..." : "Daftar Sekarang" }}
                        </Button>

                        <div class="text-center text-sm text-slate-500">
                            Sudah punya akun?

                            <Link
                                href="/login"
                                class="font-semibold text-emerald-600 hover:underline"
                            >
                                Login
                            </Link>
                        </div>
                    </div>

                    <div class="mt-8 text-center text-xs text-slate-400">
                        © 2026 Smart Hub Management System
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
