<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    // Login do usuário e geração de tokens
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $http = new Client;

        try {
            $response = $http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => '',
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true), 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Credenciais inválidas. Verifique o e-mail e a senha.'
            ], 401);
        }
    }

    // Logout do usuário
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
            return response()->json([
                'message' => 'Logout realizado com sucesso!'
            ], 200);
        }

        return response()->json([
            'error' => 'Unauthorized',
            'message' => 'Não foi possível deslogar. Usuário não autenticado.'
        ], 401);
    }

    // Renovação de tokens (Refresh Token)
    public function refreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        $http = new Client;

        try {
            $response = $http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => env('PASSPORT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                    'scope' => '',
                ],
            ]);

            return response()->json(json_decode($response->getBody(), true), 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid token',
                'message' => 'O refresh token é inválido ou expirou.'
            ], 400);
        }
    }
}