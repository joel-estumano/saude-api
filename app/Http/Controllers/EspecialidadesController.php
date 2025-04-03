<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidade;

class EspecialidadesController extends Controller
{

    /**
     * Recupera a lista completa de especialidades do banco de dados.
     *
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON contendo todas as especialidades.
     */
    public function index()
    {
        // Obtém todas as especialidades do banco de dados sem filtros ou ordenações.
        $especialidades = Especialidade::all();

        // Retorna a lista de especialidades em formato JSON com uma mensagem de sucesso.
        return response()->json([
            'message' => 'Lista de especialidades obtida com sucesso!', // Mensagem confirmando a operação bem-sucedida
            'data' => $especialidades, // Dados completos das especialidades
        ], 200); // HTTP Status 200: Operação realizada com sucesso
    }
}