<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouletteController extends Controller
{
    public function spin(Request $request)
    {
        $request->validate([
            'award' => 'required|integer|min:0'
        ]);

        $user = Auth::user();

        // Sum award into existing value
        $user->awards += $request->award;
        $user->save();

        return response()->json([
            'won' => $request->award,
            'total_awards' => $user->awards,
        ]);
    }
}
