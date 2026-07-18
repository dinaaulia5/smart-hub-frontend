<?php

namespace App\Http\Controllers;

use App\Supports\Pages\CategoryPage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return inertia('Categories/Index', [
            'pageSettings' => fn() => CategoryPage::index(),
            'items' => fn() => CategoryPage::breadcrumbs(),
        ]);
    }
    public function create(): Response
    {
        return inertia('Categories/Create', [
            'pageSettings' => fn() => CategoryPage::create(),
            'items' => fn() => CategoryPage::createBreadcrumbs(),
        ]);
    }
    public function edit(string $id): Response
    {
        return inertia('Categories/Edit', [
            'pageSettings' => fn() => CategoryPage::edit($id),
            'items' => fn() => CategoryPage::editBreadcrumbs(),
            'id' => $id,
        ]);
    }
}
