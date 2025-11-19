<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BursarDashboardController extends Controller
{
    public function index()
    {
        return view('bursar.dashboard');
    }

}
