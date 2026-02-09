<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1) Validar entrada
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string','min:4'],
        ]);

        // 2) Buscar usuario
        $user = User::where('email', $credentials['email'])->first();

        // 3) Verificar contraseña
        if ($user && Hash::check($credentials['password'], $user->password)) {

            // 4) Crear token Sanctum
            $token = $user->createToken('auth-token')->plainTextToken;

            // 5) Devolver token
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        // Revoca todos los tokens del usuario
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
}

