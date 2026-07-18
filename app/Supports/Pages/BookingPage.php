<?php

namespace App\Supports\Pages;

class BookingPage
{
    public static function index(): array
    {
        return [
            'title' => 'Booking',
            'subtitle' => 'Kelola daftar Booking untuk kebutuhan penerbangan',
            'banner' => [
                'title' => 'Master Booking',
                'subtitle' => 'Data Booking digunakan untuk mengatur informasi penerbangan.',
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'title' => 'Tambah Booking',
            'subtitle' => 'Tambahkan data Booking baru ke sistem.',
            'method' => 'POST',
            'action' => route('bookings.store'),
        ];
    }

    public static function edit($booking): array
    {
        return [
            'title' => 'Perbarui Booking',
            'subtitle' => 'Perbarui informasi Booking yang sudah terdaftar.',
            'method' => 'PUT',
            'action' => route('bookings.update', $booking),
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
                'label' => 'Booking',
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
                'label' => 'Booking',
                'href' => route('bookings.index'),
            ],
            [
                'label' => 'Tambah Booking',
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
                'label' => 'Booking',
                'href' => route('bookings.index'),
            ],
            [
                'label' => 'Perbarui Booking',
            ],
        ];
    }
}
