<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function dashboard()
    {
        return view('branch-views.dashboard');
    }


    public function settings()
    {
        return view('branch-views.settings');
    }
}