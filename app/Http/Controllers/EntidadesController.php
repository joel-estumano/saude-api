<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entidade;

class EntidadesController extends Controller
{

    public function store(Request $request)
    {
        try {
            // Validação dos dados
            $this->validate($request, [
                'razao_social' => 'required|string|max:255',
                'nome_fantasia' => 'required|string|max:255',
                'cnpj' => 'required|string|max:18|unique:entidades,cnpj',
                'regional_id' => 'required|exists:regionais,uuid',
                'data_inauguracao' => 'required|date',
                'ativa' => 'required|boolean',
                'especialidades' => 'required|array|min:5',
                'especialidades.*' => 'required|exists:especialidades,uuid',
            ]);

            // Acesse os dados validados diretamente no request
            $entidadeData = $request->only([
                'razao_social',
                'nome_fantasia',
                'cnpj',
                'regional_id',
                'data_inauguracao',
                'ativa',
            ]);

            // Cria a entidade
            $entidade = Entidade::create($entidadeData);

            // Sincronizar especialidades
            if ($request->has('especialidades')) {
                $entidade->especialidades()->sync($request->especialidades);
            }

            return response()->json([
                'message' => 'Entidade criada com sucesso!',
                'data' => $entidade->load('especialidades'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar a entidade.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        try {
            // Validação com ignorância de unicidade para o CNPJ da própria entidade
            $validatedData = $this->validate($request, [
                'razao_social' => 'nullable|string|max:255',
                'nome_fantasia' => 'nullable|string|max:255',
                'cnpj' => 'nullable|string|max:18|unique:entidades,cnpj,' . $uuid . ',uuid',
                'regional_id' => 'nullable|exists:regionais,uuid',
                'data_inauguracao' => 'nullable|date',
                'ativa' => 'nullable|boolean',
                'especialidades' => 'sometimes|array|min:5',
                'especialidades.*' => 'required|exists:especialidades,uuid',
            ]);

            // Buscar a entidade pelo UUID
            $entidade = Entidade::findOrFail($uuid);

            // Atualizar os dados da entidade
            $entidadeData = $validatedData;
            unset($entidadeData['especialidades']); // Remove especialidades antes do update
            $entidade->update($entidadeData);

            // Sincronizar especialidades, se fornecidas
            if ($request->has('especialidades')) {
                $entidade->especialidades()->sync($validatedData['especialidades']);
            }

            return response()->json([
                'message' => 'Entidade atualizada com sucesso!',
                'data' => $entidade->load('especialidades'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar a entidade.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
