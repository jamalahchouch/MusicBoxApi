<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentative d'authentification
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => '❌ Identifiants invalides'
            ], 401);
        }

        $user = Auth::user();

        // Création d’un token API pour Postman
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => '✅ Utilisateur connecté avec succès',
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
