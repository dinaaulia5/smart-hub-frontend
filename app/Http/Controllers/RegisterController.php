<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;

class RegisterController extends Controller
{
    public function index(): Response
    {
        return inertia('Auth/Register');
    }
}
