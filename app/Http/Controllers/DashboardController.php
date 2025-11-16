<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $websiteCount = $user->websites()->count();
        $websites = $user->websites()->with('domains')->latest()->take(5)->get();

        return view('dashboard', compact('user', 'websiteCount', 'websites'));
    }
}
