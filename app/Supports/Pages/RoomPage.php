<?php

namespace App\Supports\Pages;

class RoomPage
{
    public static function index(): array
    {
        return [
            'title' => 'Room',
            'subtitle' => 'Kelola daftar ruangan yang tersedia.',
            'banner' => [
                'title' => 'Master Room',
                'subtitle' => 'Data room digunakan untuk mengelola informasi ruangan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Room',
            'subtitle' => 'Tambahkan data room baru ke sistem.',
            'method' => 'POST',
            'action' => route('rooms.store'),
        ];
    }

    public static function edit($room): array
    {
        return [
            'title' => 'Perbarui Room',
            'subtitle' => 'Perbarui informasi room yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('rooms.update', $room),
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
                'label' => 'Room',
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
                'label' => 'Room',
                'href' => route('rooms.index'),
            ],
            [
                'label' => 'Tambah Room',
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
                'label' => 'Room',
                'href' => route('rooms.index'),
            ],
            [
                'label' => 'Perbarui Room',
            ],
        ];
    }
}
