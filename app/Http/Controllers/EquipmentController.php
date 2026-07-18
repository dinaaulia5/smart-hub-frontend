<?php

namespace App\Http\Controllers;

use App\Supports\Pages\EquipmentPage;
use Inertia\Response;

class EquipmentController extends Controller
{

    public function index(): Response
    {
        return inertia('Equipments/Index', [
            'pageSettings' => fn() => EquipmentPage::index(),
            'items' => fn() => EquipmentPage::breadcrumbs(),
        ]);
    }


    public function create(): Response
    {
        return inertia('Equipments/Create', [
            'pageSettings' => fn() => EquipmentPage::create(),
            'items' => fn() => EquipmentPage::breadcrumbs(),
        ]);
    }


    public function show(string $id): Response
    {
        return inertia('Equipments/Show', [
            'pageSettings' => fn() => EquipmentPage::show($id),
            'items' => fn() => EquipmentPage::breadcrumbs(),
        ]);
    }


    public function edit(string $id): Response
    {
        return inertia('Equipments/Edit', [
            'pageSettings' => fn() => EquipmentPage::edit($id),
            'items' => fn() => EquipmentPage::editBreadcrumbs(),
            'id' => $id,
        ]);
    }
}
