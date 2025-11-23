<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index()
    {
        // this is to return the  view('parent.dashboard');
        return app(ParentController::class)->index();
    }
}

