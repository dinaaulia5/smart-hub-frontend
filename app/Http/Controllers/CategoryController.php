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
}
