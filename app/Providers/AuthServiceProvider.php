<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento das políticas de autorização para a aplicação.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy', // Mapeia o modelo genérico para sua respectiva política
    ];

    /**
     * Registra os serviços de autenticação e autorização.
     *
     * Este método é executado automaticamente durante o boot do sistema
     * e é responsável por configurar políticas e rotas relacionadas ao OAuth2 com Passport.
     *
     * @return void
     */
    public function boot()
    {
        // Registra as políticas de autorização mapeadas acima
        $this->registerPolicies();

        // Registra as rotas necessárias para a autenticação OAuth2 com o Passport
        Passport::routes();

        // Define a expiração dos tokens de acesso para 30 minutos a partir do momento em que são gerados
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));

        // Define a expiração dos tokens de atualização apenas 1 dia
        Passport::refreshTokensExpireIn(Carbon::now()->addDay());
    }
}
