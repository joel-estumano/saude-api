<?php

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\Auth;
use App\User;

/**
 * Controlador personalizado para autenticação de usuários usando Laravel Passport.
 *
 * Este controlador estende o AccessTokenController padrão do Passport para emitir tokens
 * e adicionar informações extras do usuário na resposta ao login.
 */
class AuthController extends AccessTokenController
{
    /**
     * Realiza o login do usuário e retorna o token de acesso junto com os dados do usuário.
     *
     * @param ServerRequestInterface $request Requisição que contém as credenciais do usuário e dados OAuth.
     * @return \Illuminate\Http\JsonResponse Resposta JSON com token e dados do usuário ou mensagem de erro.
     */
    public function login(ServerRequestInterface $request)
    {
        // Obtem o token padrão utilizando o fluxo OAuth2 do Laravel Passport.
        $tokenResponse = parent::issueToken($request);

        // Decodifica o conteúdo do token gerado, transformando a resposta em um array.
        $content = json_decode($tokenResponse->getContent(), true);

        // Verifica se ocorreu algum erro na geração do token (como credenciais inválidas).
        if (isset($content['error'])) {
            return response()->json(['error' => $content['error']], 401);
        }

        // Busca o usuário autenticado diretamente pelo e-mail fornecido na requisição.
        $user = User::where('email', $request->getParsedBody()['username'])->first();

        // Caso o usuário não seja encontrado, retorna uma mensagem de erro.
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Retorna uma resposta JSON com o token gerado e os dados do usuário.
        return response()->json([
            'token_type' => $content['token_type'],   
            'expires_in' => $content['expires_in'],    
            'access_token' => $content['access_token'], 
            'refresh_token' => $content['refresh_token'],
            'user' => [
                'id' => $user->id,                    
                'name' => $user->name,                 
                'email' => $user->email,             
            ],
        ]);
    }
}