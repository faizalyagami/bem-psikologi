<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $statistics = [
            'users' => User::count(),
            'posts' => 0,
            'pages' => 0,
            'events' => 0,
        ];

        return view('admin.dashboard.index', compact('statistics'));
    }
}
