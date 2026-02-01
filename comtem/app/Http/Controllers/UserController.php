<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function userProfile()
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // Return JSON for API calls, redirect for page requests
            if (request()->expectsJson()) {
                return response()->json([
                    'isLoggedIn' => false,
                    'user' => null
                ]);
            }
            return redirect()->route('login');
        }

        // Fetch the authenticated user
        $user = auth()->user();
        $user->load(['family']);

        // Return data to the Vue component via Inertia
        return Inertia::render('User', [
            'user' => $user
        ]);
    }

    public function getFamily()
    {
        $user = Auth::user();
        $user->load('family'); // Load the family relationship

        return response()->json([
            'family' => $user->family,
        ]);
    }

    public function getAward()
    {
        $user = Auth::user();
        return response()->json([
            'award' => $user->award,
        ]);
    }

    public function getProfile()
    {
        $user = Auth::user();
        return response()->json([
            'address' => $user->address,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
