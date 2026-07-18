<?php

namespace App\Http\Controllers;


use App\Supports\Pages\RoomPage;
use Inertia\Response;

class RoomController extends Controller
{

    public function index(): Response
    {
        return inertia('Rooms/Index', [
            'pageSettings' => fn() => RoomPage::index(),
            'items' => fn() => RoomPage::breadcrumbs(),
        ]);
    }


    public function create(): Response
    {
        return inertia('Rooms/Create', [
            'pageSettings' => fn() => RoomPage::create(),
            'items' => fn() => RoomPage::breadcrumbs(),
        ]);
    }


    public function show(string $id): Response
    {
        return inertia('Rooms/Show', [
            'pageSettings' => fn() => RoomPage::show($id),
            'items' => fn() => RoomPage::breadcrumbs(),
        ]);
    }

    public function edit(string $id): Response
    {
        return inertia('Rooms/Edit', [
            'pageSettings' => fn() => RoomPage::edit($id),
            'items' => fn() => RoomPage::breadcrumbs(),
            'id' => $id,
        ]);
    }
}
