<?php

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use App\User;

/**
 * Controlador personalizado para autenticação de usuários usando Laravel Passport.
 *
 * Este controlador estende o AccessTokenController padrão do Passport para emitir tokens
 * e adicionar informações extras do usuário na resposta ao login e à renovação do token.
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
        // Obtém o token padrão utilizando o fluxo OAuth2 do Laravel Passport.
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

    /**
     * Renova o token de acesso utilizando o refresh token e retorna os dados do usuário.
     *
     * @param ServerRequestInterface $request Requisição que contém o refresh token.
     * @return \Illuminate\Http\JsonResponse Resposta JSON com o novo token e dados do usuário ou mensagem de erro.
     */
    public function refreshToken(ServerRequestInterface $request)
    {
        // Obtém os novos tokens usando o fluxo padrão OAuth2 do Passport.
        $tokenResponse = parent::issueToken($request);

        // Decodifica o conteúdo do token gerado.
        $content = json_decode($tokenResponse->getContent(), true);

        // Verifica se ocorreu algum erro na geração do token.
        if (isset($content['error'])) {
            return response()->json(['error' => $content['error']], 401);
        }

        // Recupera o `access_token` recém-gerado.
        $accessToken = $content['access_token'];

        try {
            // Decodifica o payload do JWT manualmente.
            $parts = explode('.', $accessToken); // Divida o token em três partes.
            if (count($parts) !== 3) {
                throw new \Exception('Token inválido');
            }

            // O payload é a segunda parte do JWT.
            $payload = json_decode(base64_decode($parts[1]), true);

            if (!is_array($payload) || !isset($payload['sub'])) {
                throw new \Exception('Payload inválido ou sem informações do usuário');
            }

            // Extrai o ID do usuário da claim 'sub'.
            $userId = $payload['sub'];

            // Busca os dados do usuário na tabela `users`.
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

            // Retorna os novos tokens junto com os dados do usuário.
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
        } catch (\Exception $e) {
            // Retorna erro caso algo dê errado na decodificação.
            return response()->json(['error' => 'Falha ao processar o token: ' . $e->getMessage()], 400);
        }
    }
}