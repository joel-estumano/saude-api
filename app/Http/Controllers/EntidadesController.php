<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entidade;
use App\Models\Regional;

class EntidadesController extends Controller
{

    /**
     * Armazena uma nova entidade no banco de dados.
     *
     * @param Request $request Objeto da requisição contendo os dados da entidade.
     * @return \Illuminate\Http\JsonResponse Retorna a resposta JSON com os dados da entidade criada ou mensagens de erro.
     */
    public function store(Request $request)
    {
        try {
            // Valida os dados enviados na requisição
            $this->validate($request, [
                'razao_social' => 'required|string|max:255', // Obrigatório, string, tamanho máximo de 255 caracteres
                'nome_fantasia' => 'required|string|max:255', // Obrigatório, string, tamanho máximo de 255 caracteres
                'cnpj' => 'required|string|max:18|unique:entidades,cnpj', // Obrigatório, string, tamanho máximo de 18 caracteres, deve ser único na tabela 'entidades'
                'regional_id' => 'required|exists:regionais,uuid', // Obrigatório, deve existir na tabela 'regionais' com base no UUID
                'data_inauguracao' => 'required|date', // Obrigatório, deve ser uma data válida
                'ativa' => 'required|boolean', // Obrigatório, deve ser um booleano (true ou false)
                'especialidades' => 'required|array|min:5', // Obrigatório, array com no mínimo 5 itens
                'especialidades.*' => 'required|exists:especialidades,uuid', // Cada item deve existir na tabela 'especialidades' com base no UUID
            ]);

            // Extrai os dados específicos da requisição para criar a entidade
            $entidadeData = $request->only([
                'razao_social',
                'nome_fantasia',
                'cnpj',
                'regional_id',
                'data_inauguracao',
                'ativa',
            ]);

            // Cria a entidade no banco de dados com os dados validados
            $entidade = Entidade::create($entidadeData);

            // Sincroniza as especialidades associadas, caso fornecidas na requisição
            if (!empty($request->especialidades)) {
                $entidade->especialidades()->sync($request->especialidades);
            }

            // Carrega os relacionamentos 'especialidades' e 'regional' para incluir nos dados de resposta
            $entidade->load(['especialidades', 'regional']);

            // Retorna uma resposta JSON indicando sucesso na criação da entidade
            return response()->json([
                'message' => 'Entidade criada com sucesso!', // Mensagem de sucesso
                'data' => $entidade, // Dados completos da entidade criada
            ], 201); // HTTP Status 201: Criado com sucesso

        } catch (ValidationException $e) {
            // Captura erros de validação e retorna uma resposta com status 400
            return response()->json([
                'message' => 'Erro de validação.', // Mensagem de erro
                'details' => $e->validator->errors()->toArray(), // Detalhes dos erros de validação
            ], 400); // HTTP Status 400: Requisição inválida

        } catch (Exception $e) {
            // Captura qualquer outro erro inesperado durante o processo e retorna com status 500
            return response()->json([
                'message' => 'Erro ao criar a entidade.', // Mensagem de erro genérico
                'error' => $e->getMessage(), // Detalhe do erro capturado
            ], 500); // HTTP Status 500: Erro interno no servidor
        }
    }
   
    public function show($uuid)
    {
        // Encontra a entidade pelo UUID e carrega as relações 'especialidades' e 'regional'
        $entidade = Entidade::with(['especialidades', 'regional'])->findOrFail($uuid);
    
        return response()->json([
            'message' => 'Entidade recuperada com sucesso!',
            'data' => $entidade
        ]);
    }


    public function index(Request $request)
{
    // Paginação e filtro de texto
    $perPage = $request->get('per_page', 10); // Valor padrão: 10 itens por página
    $text = $request->get('text', '');       // Valor padrão para o filtro de texto
    $sortField = $request->get('sort_field', 'created_at'); // Campo para ordenação (padrão: "created_at")
    $sortOrder = $request->get('sort_order', 'desc');          // Direção da ordenação (padrão: "desc")

    // Inicializa a query base
    $entidadesQuery = Entidade::with(['especialidades', 'regional']); // Carrega os dados completos de 'regional'

    // Aplicando o filtro de texto, se fornecido
    if (!empty($text)) {
        $entidadesQuery->where(function ($query) use ($text) {
            $query->where('razao_social', 'LIKE', "%{$text}%")
                  ->orWhere('nome_fantasia', 'LIKE', "%{$text}%")
                  ->orWhere('cnpj', 'LIKE', "%{$text}%");
        })->orWhereHas('regional', function ($query) use ($text) {
            $query->where('label', 'LIKE', "%{$text}%");
        });
    }

    // Verifica o campo para ordenação e aplica o join, se necessário
    if ($sortField === 'regional') {
        $entidadesQuery->join('regionais', 'entidades.regional_id', '=', 'regionais.uuid')
                       ->select('entidades.*', 'regionais.label') // Inclui 'label' no SELECT
                       ->orderBy('regionais.label', $sortOrder);
    } else {
        // Ordenação para campos diretos da tabela 'entidades'
        $entidadesQuery->orderBy($sortField, $sortOrder);
    }

    // Paginação
    $entidades = $entidadesQuery->paginate($perPage);

    // Garantindo que o objeto regional seja populado na resposta
    foreach ($entidades as $entidade) {
        $entidade->regional = Regional::find($entidade->regional_id); // Carrega o objeto completo de 'regional'
    }

    // Retorno da resposta JSON
    return response()->json([
        'message' => 'Lista de entidades obtida com sucesso.',
        'data' => $entidades,
    ], 200);
}
    // public function index(Request $request)
    // {  //sleep(2);
    //     $perPage = $request->get('per_page'); // Valor padrão para paginação
    //     $text = $request->get('text', ''); // Valor padrão para o filtro de texto

    //     $entidadesQuery = Entidade::with(['especialidades', 'regional']);

    //     // Aplicando o filtro de texto, se fornecido
    //     if (!empty($text)) {
    //         $entidadesQuery->where(function ($query) use ($text) {
    //             // Filtro para as colunas da tabela 'entidades'
    //             $query->where('razao_social', 'LIKE', "%{$text}%")
    //                 ->orWhere('nome_fantasia', 'LIKE', "%{$text}%")
    //                 ->orWhere('cnpj', 'LIKE', "%{$text}%");
    //         })
    //         ->orWhereHas('regional', function ($query) use ($text) {
    //             // Filtro para as colunas da tabela 'regionais'
    //             $query->where('label', 'LIKE', "%{$text}%");
    //         });
    //     }

    //     // Paginação
    //     $entidades = $entidadesQuery->paginate($perPage);

    //     // Retorno da resposta JSON
    //     return response()->json([
    //         'message' => 'Lista de entidades obtida com sucesso.',
    //         'data' => $entidades,
    //     ], 200);
    // }

    public function update(Request $request, $uuid)
    {
        try {
            // Validação com ignorância de unicidade para o CNPJ da própria entidade
            $this->validate($request, [
                'razao_social' => 'nullable|string|max:255',
                'nome_fantasia' => 'nullable|string|max:255',
                'cnpj' => 'nullable|string|max:18|unique:entidades,cnpj,' . $uuid . ',uuid',
                'regional_id' => 'nullable|exists:regionais,uuid',
                'data_inauguracao' => 'nullable|date',
                'ativa' => 'nullable|boolean',
                'especialidades' => 'nullable|array',
                'especialidades.*' => 'exists:especialidades,uuid',
            ]);

            // Buscar a entidade pelo UUID
            $entidade = Entidade::with(['especialidades', 'regional'])->findOrFail($uuid);

            // Atualizar os dados da entidade
            $entidadeData = $request->only([
                'razao_social',
                'nome_fantasia',
                'cnpj',
                'regional_id',
                'data_inauguracao',
                'ativa',
            ]);

            $entidade->update($entidadeData);

            // Sincronizar especialidades, se fornecidas
            if (!empty($request->especialidades)) {
                $entidade->especialidades()->sync($request->especialidades);
            }

            return response()->json([
                'message' => 'Entidade atualizada com sucesso!',
                'data' => $entidade->load('especialidades'),
            ], 200);

        } catch (ValidationException $e) {
            // Retorna erros de validação diretamente
            return response()->json([
                'message' => 'Erro de validação.',
                'details' => $e->validator->errors()->toArray(),
            ], 400);
        } catch (Exception $e) {
            // Retorna erros genéricos
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
