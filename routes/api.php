<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rotas públicas (sem autenticação necessária)
Route::post('login', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
Route::post('refresh', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
Route::get('logout', function (Request $request) {
    $user = $request->user();

    // Revogar todos os tokens emitidos para o usuário
    foreach ($user->tokens as $token) {
        $token->revoke();
    }

    return response()->json([
        'message' => 'Todos os tokens foram revogados com sucesso!',
    ]);
})->middleware('auth:api');

// Rotas protegidas por middleware auth:api
Route::middleware(['auth:api'])->group(function () {

    // Rota para obter informações do usuário autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rotas para Regionais
    Route::get('regionais', 'RegionaisController@index');

    // Rotas para Especialidades
    Route::get('especialidades', 'EspecialidadesController@index');

    // Rotas para Entidades
    Route::post('entidades', 'EntidadesController@store');
    Route::get('entidades/{uuid}', 'EntidadesController@show');
    Route::get('entidades', 'EntidadesController@index');
    Route::put('entidades/{uuid}', 'EntidadesController@update');
    Route::delete('entidades/{uuid}', 'EntidadesController@destroy');
});