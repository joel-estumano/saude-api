<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regional;

class RegionaisController extends Controller
{
  
    /**
     * Recupera a lista completa de regionais do banco de dados.
     *
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON contendo todas as regionais.
     */
    public function index()
    {
        // Obtém todas as regionais do banco de dados sem filtros ou ordenações.
        $regionais = Regional::all();

        // Retorna a lista de regionais em formato JSON com uma mensagem de sucesso.
        return response()->json([
            'message' => 'Lista de regionais obtida com sucesso.', // Mensagem de confirmação
            'data' => $regionais // Dados completos das regionais
        ], 200); // HTTP Status 200: Operação realizada com sucesso
    }
}