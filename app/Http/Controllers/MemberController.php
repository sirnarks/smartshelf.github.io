<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\StatsOverview;

class MemberController extends Controller
{
    public function dashboard()
    {
        // Optional: Only allow non-admin users
        if (Auth::user()->role === 'admin') {
            return redirect('/admin');
        }

        // Get widget data (simulate Filament widget)
        $stats = (new StatsOverview())->getCards();

        return view('dashboard', compact('stats'));
    }
}
