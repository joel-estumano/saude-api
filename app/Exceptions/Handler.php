<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;
use Carbon\Carbon;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Verifica se a requisição é para a API (baseado no prefixo /api)
        if ($request->is('api/*')) {
            // Define o status e a mensagem com base no tipo da exceção
            if ($exception instanceof AuthenticationException) {
                $status = 401; // Código para "Não Autenticado"
                $message = 'Unauthenticated'; // Mensagem padrão
            } elseif ($exception instanceof ValidationException) {
                $status = 400; // Código para erro de validação
                $message = $exception->validator->errors(); // Detalhes dos erros de validação
            } else {
                $status = method_exists($exception, 'getStatusCode') 
                    ? $exception->getStatusCode() 
                    : 500; // Código padrão para erro interno
                $message = $exception->getMessage(); // Mensagem de erro geral
            }

            // Retorna a resposta padronizada em JSON
            return response()->json([
                'status' => $status,
                'timestamp' => Carbon::now()->toIso8601String(), // Timestamp no padrão ISO 8601
                'path' => $request->path(), // Caminho da requisição
                'error' => [
                    'message' => $message, // Mensagem de erro
                ],
            ], $status);
        }

        // Chamada não-API: utiliza o comportamento padrão
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
