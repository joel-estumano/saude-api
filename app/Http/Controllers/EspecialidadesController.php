<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidade;

class EspecialidadesController extends Controller
{

    public function index()
    {
    
        $especialidades = Especialidade::all();

        return response()->json([
            'message' => 'Lista de especialidades obtida com sucesso!',
            'data' => $especialidades,
        ], 200);
    }
}