<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login do usuário
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'token' => $token
        ], 200);
    }

    // Logout do usuário
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ], 200);
    }

    // Refresh token (exemplo simplificado)
    public function refresh(Request $request)
    {
        // Retorne um novo token ou lógica de refresh aqui
        return response()->json([
            'message' => 'Token atualizado',
            'token' => 'novo_token_gerado_aqui'
        ]);
    }
}