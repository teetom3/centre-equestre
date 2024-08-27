<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche la vue du tableau de bord.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retourne la vue du tableau de bord située dans resources/views/users/dashboard.blade.php
        return view('users.dashboard'); 
    }
}
