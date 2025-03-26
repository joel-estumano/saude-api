<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regional;

class RegionaisController extends Controller
{
    /**
     * Lista todos os registros de Regional.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        
        $regionais = Regional::all();

        return response()->json([
            'message' => 'Lista de regionais obtida com sucesso.',
            'data' => $regionais
        ], 200);
    }
}