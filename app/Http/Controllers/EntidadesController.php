<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entidade;

class EntidadesController extends Controller
{
   
    public function store(Request $request)
    {
        
        $validatedData = $this->validate($request, [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:entidade,cnpj',
            'regional_id' => 'required|exists:regional,uuid',
            'data_inauguracao' => 'required|date',
            'ativa' => 'required|boolean',
            'especialidades' => 'required|array|min:5', 
            'especialidades.*' => 'required|exists:especialidade,uuid', 
        ]);

        
        $entidade = Entidade::create($validatedData);

        
        $entidade->especialidades()->sync($validatedData['especialidades']);

        return response()->json([
            'message' => 'Entidade criada com sucesso!',
            'data' => $entidade
        ], 201);
    }

    public function show($uuid)
    {
        
        $entidade = Entidade::findOrFail($uuid);

       
        return response()->json([
            'message' => 'Entidade recuperada com sucesso!',
            'data' => $entidade
        ]);
    }

    /**
     * Lista entidades de forma paginada.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        
        $perPage = $request->get('per_page', 10);

      
        $entidades = Entidade::paginate($perPage);

        
        return response()->json([
            'message' => 'Lista de entidades obtida com sucesso.',
            'data' => $entidades,
        ], 200);
    }
    
    public function update(Request $request, $uuid)
    {
       
        $validatedData = $this->validate($request, [
            'razao_social' => 'nullable|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18|unique:entidade,cnpj,' . $uuid . ',uuid',
            'regional_id' => 'nullable|exists:regional,uuid',
            'data_inauguracao' => 'nullable|date',
            'ativa' => 'nullable|boolean',
        ]);

        
        $entidade = Entidade::findOrFail($uuid);
        $entidade->update($request->all());

        return response()->json([
            'message' => 'Entidade atualizada com sucesso!',
            'data' => $entidade
        ], 200);
    }

   
    public function destroy($uuid)
    {
        $entidade = Entidade::findOrFail($uuid);
        $entidade->delete();

        return response()->json([
            'message' => 'Entidade excluída com sucesso!'
        ]);
    }
}
