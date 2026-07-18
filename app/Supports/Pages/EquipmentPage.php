<?php

namespace App\Supports\Pages;

class EquipmentPage
{
    public static function index(): array
    {
        return [
            'title' => 'Equipment',
            'subtitle' => 'Kelola daftar equipment yang tersedia.',
            'banner' => [
                'title' => 'Master Equipment',
                'subtitle' => 'Data equipment digunakan untuk mengelola fasilitas dan perlengkapan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Equipment',
            'subtitle' => 'Tambahkan data equipment baru ke sistem.',
            'method' => 'POST',
            'action' => route('equipments.store'),
        ];
    }

    public static function edit($equipment): array
    {
        return [
            'title' => 'Perbarui Equipment',
            'subtitle' => 'Perbarui informasi equipment yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('equipments.update', $equipment),
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
                'label' => 'Equipment',
            ],
        ];
    }

    public static function createBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Equipment',
                'href' => route('equipments.index'),
            ],
            [
                'label' => 'Tambah Equipment',
            ],
        ];
    }

    public static function editBreadcrumbs(): array
    {
        return [
            [
                'label' => 'Dashboard',
                'href' => route('dashboard'),
            ],
            [
                'label' => 'Equipment',
                'href' => route('equipments.index'),
            ],
            [
                'label' => 'Perbarui Equipment',
            ],
        ];
    }
}
