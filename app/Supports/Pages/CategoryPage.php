<?php

namespace App\Supports\Pages;

class CategoryPage
{
    public static function index(): array
    {
        return [
            'title' => 'Kategori',
            'subtitle' => 'Kelola daftar Kategori untuk kebutuhan penerbangan',
            'banner' => [
                'title' => 'Master Kategori',
                'subtitle' => 'Data Kategori digunakan untuk mengatur informasi penerbangan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Kategori',
            'subtitle' => 'Tambahkan data Kategori baru ke sistem.',
            'method' => 'POST',
            'action' => route('airlines.store'),
        ];
    }

    public static function edit($airline): array
    {
        return [
            'title' => 'Perbarui Kategori',
            'subtitle' => 'Perbarui informasi Kategori yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('airlines.update', $airline),
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
                'label' => 'Kategori',
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
                'label' => 'Kategori',
                'href' => route('airlines.index'),
            ],
            [
                'label' => 'Tambah Kategori',
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
                'label' => 'Kategori',
                'href' => route('airlines.index'),
            ],
            [
                'label' => 'Perbarui Kategori',
            ],
        ];
    }
}
