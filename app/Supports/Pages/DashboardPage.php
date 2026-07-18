<?php

namespace App\Supports\Pages;

class DashboardPage
{
    public static function index(): array
    {
        return [
            'title' => 'Dashboard',
            'subtitle' => 'Pantau aktivitas peminjaman ruangan, peralatan, dan proses check-in secara real-time.',
            'banner' => [
                'title' => 'Selamat Datang di Smart Hub Management',
                'subtitle' => 'Kelola data pengguna, kategori, ruangan, peralatan, serta pantau seluruh aktivitas booking dan check-in dalam satu dashboard.',
            ],
        ];
    }

    public static function breadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Dashboard',
            ],
        ];
    }
}
