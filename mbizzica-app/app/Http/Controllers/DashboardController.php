<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Paste;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentPastes = Paste::where('user_id', $user->id)->latest()->take(5)->get();

        return view('dashboard', compact('user', 'recentPastes'));
    }
}
