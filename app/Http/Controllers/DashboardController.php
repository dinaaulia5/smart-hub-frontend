<?php

namespace App\Http\Controllers;

use App\Supports\Pages\DashboardPage;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'pageSettings' => fn() => DashboardPage::index(),
            'items' => fn() => DashboardPage::breadcrumbs(),
        ]);
    }
}
