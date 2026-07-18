<?php

namespace App\Http\Controllers;

use App\Supports\Pages\BookingPage;
use Illuminate\Http\Request;
use Inertia\Response;

class BookingController extends Controller
{
    public function index(): Response
    {
        return inertia('Bookings/Index', [
            'pageSettings' => fn() => BookingPage::index(),
            'items' => fn() => BookingPage::breadcrumbs(),
        ]);
    }

    public function create(): Response
    {
        return inertia('Bookings/Create', [
            'pageSettings' => fn() => BookingPage::create(),
            'items' => fn() => BookingPage::createBreadcrumbs(),
        ]);
    }
    public function edit(string $id): Response
    {
        return inertia('Bookings/Edit', [
            'pageSettings' => fn() => BookingPage::edit($id),
            'items' => fn() => BookingPage::editBreadcrumbs(),
            'id' => $id,
        ]);
    }
}
