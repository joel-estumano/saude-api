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
   
    /**
     * Recupera uma entidade específica com base no UUID fornecido.
     *
     * @param string $uuid O identificador único da entidade que será recuperada.
     * @return \Illuminate\Http\JsonResponse Retorna a resposta JSON contendo os dados da entidade encontrada ou erro caso não seja localizada.
     */
    public function show($uuid)
    {
        // Localiza a entidade no banco de dados pelo UUID e carrega os relacionamentos 'especialidades' e 'regional'.
        // Caso o UUID não seja encontrado, lança uma exceção automaticamente.
        $entidade = Entidade::with(['especialidades', 'regional'])->findOrFail($uuid);

        // Retorna uma resposta JSON com os dados da entidade recuperada e uma mensagem de sucesso.
        return response()->json([
            'message' => 'Entidade recuperada com sucesso!', // Mensagem informando sucesso na operação
            'data' => $entidade // Dados completos da entidade encontrada, incluindo especialidades e regional
        ]);
    }

    /**
     * Lista as entidades com suporte para paginação, filtro de texto e ordenação.
     *
     * @param Request $request Objeto da requisição contendo parâmetros opcionais para paginação, ordenação e filtro.
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON contendo a lista de entidades e metadados da paginação.
     */
    public function index(Request $request)
    {
        // Paginação e filtro de texto
        $perPage = $request->get('per_page', 10); // Quantidade de itens por página (padrão: 10)
        $text = $request->get('text', '');       // Filtro para pesquisa textual (padrão: string vazia)
        $sortField = $request->get('sort_field', 'created_at'); // Campo de ordenação (padrão: "created_at")
        $sortOrder = $request->get('sort_order', 'desc');       // Direção da ordenação (padrão: "desc")

        // Inicializa a consulta base para buscar entidades com seus relacionamentos
        $entidadesQuery = Entidade::with(['especialidades', 'regional']); // Carrega 'especialidades' e 'regional'

        // Aplicando filtro de texto, se fornecido
        if (!empty($text)) {
            $entidadesQuery->where(function ($query) use ($text) {
                // Filtra pelos campos 'razao_social', 'nome_fantasia' e 'cnpj'
                $query->where('razao_social', 'LIKE', "%{$text}%")
                    ->orWhere('nome_fantasia', 'LIKE', "%{$text}%")
                    ->orWhere('cnpj', 'LIKE', "%{$text}%");
            })->orWhereHas('regional', function ($query) use ($text) {
                // Filtra pelo campo 'label' da tabela relacionada 'regionais'
                $query->where('label', 'LIKE', "%{$text}%");
            });
        }

        // Aplica a ordenação com base no campo solicitado
        if ($sortField === 'regional') {
            // Se o campo de ordenação for 'regional', realiza um join com a tabela 'regionais'
            $entidadesQuery->join('regionais', 'entidades.regional_id', '=', 'regionais.uuid')
                        ->select('entidades.*', 'regionais.label') // Inclui o campo 'label' de 'regionais'
                        ->orderBy('regionais.label', $sortOrder);  // Ordena pelo 'label' da tabela 'regionais'
        } else {
            // Ordenação pelos campos da tabela 'entidades'
            $entidadesQuery->orderBy($sortField, $sortOrder);
        }

        // Realiza a paginação com base no número de itens por página
        $entidades = $entidadesQuery->paginate($perPage);

        // Garante que o objeto 'regional' seja populado corretamente em cada entidade
        foreach ($entidades as $entidade) {
            $entidade->regional = Regional::find($entidade->regional_id); // Carrega o objeto completo de 'regional'
        }

        // Retorna a lista de entidades em formato JSON
        return response()->json([
            'message' => 'Lista de entidades obtida com sucesso.', // Mensagem de sucesso
            'data' => $entidades, // Dados paginados das entidades
        ], 200); // HTTP Status 200: Sucesso
    }
    
    /**
     * Atualiza uma entidade existente com base no UUID fornecido.
     *
     * @param Request $request Objeto da requisição contendo os dados a serem atualizados.
     * @param string $uuid Identificador único da entidade a ser atualizada.
     * @return \Illuminate\Http\JsonResponse Retorna a resposta JSON contendo a entidade atualizada ou mensagens de erro.
     */
    public function update(Request $request, $uuid)
    {
        try {
            // Valida os dados enviados na requisição
            $this->validate($request, [
                'razao_social' => 'nullable|string|max:255', // Opcional, string, com no máximo 255 caracteres
                'nome_fantasia' => 'nullable|string|max:255', // Opcional, string, com no máximo 255 caracteres
                'cnpj' => 'nullable|string|max:18|unique:entidades,cnpj,' . $uuid . ',uuid', // Opcional, string, deve ser único na tabela (exceto para a entidade atual)
                'regional_id' => 'nullable|exists:regionais,uuid', // Opcional, deve ser um UUID válido existente na tabela 'regionais'
                'data_inauguracao' => 'nullable|date', // Opcional, deve ser uma data válida
                'ativa' => 'nullable|boolean', // Opcional, deve ser um valor booleano (true ou false)
                'especialidades' => 'nullable|array', // Opcional, deve ser um array
                'especialidades.*' => 'exists:especialidades,uuid', // Cada item no array deve existir na tabela 'especialidades'
            ]);

            // Busca a entidade no banco de dados pelo UUID, com seus relacionamentos 'especialidades' e 'regional'
            $entidade = Entidade::with(['especialidades', 'regional'])->findOrFail($uuid);

            // Extrai os dados da requisição para atualização
            $entidadeData = $request->only([
                'razao_social',
                'nome_fantasia',
                'cnpj',
                'regional_id',
                'data_inauguracao',
                'ativa',
            ]);

            // Atualiza os dados da entidade com os valores validados
            $entidade->update($entidadeData);

            // Sincroniza as especialidades fornecidas na requisição, se existirem
            if (!empty($request->especialidades)) {
                $entidade->especialidades()->sync($request->especialidades);
            }

            // Retorna uma resposta JSON com a entidade atualizada e os relacionamentos recarregados
            return response()->json([
                'message' => 'Entidade atualizada com sucesso!', // Mensagem de sucesso
                'data' => $entidade->load('especialidades'), // Dados completos da entidade atualizada
            ], 200); // HTTP Status 200: Requisição bem-sucedida

        } catch (ValidationException $e) {
            // Captura erros de validação e retorna uma resposta com status 400
            return response()->json([
                'message' => 'Erro de validação.', // Mensagem informando erro de validação
                'details' => $e->validator->errors()->toArray(), // Detalhes dos erros encontrados
            ], 400); // HTTP Status 400: Requisição inválida

        } catch (Exception $e) {
            // Captura quaisquer outros erros inesperados e retorna com status 500
            return response()->json([
                'message' => 'Erro ao atualizar a entidade.', // Mensagem genérica de erro
                'error' => $e->getMessage(), // Detalhe técnico do erro
            ], 500); // HTTP Status 500: Erro interno no servidor
        }
    }

   /**
     * Remove uma entidade existente com base no UUID fornecido.
     *
     * @param string $uuid O identificador único da entidade que será removida.
     * @return \Illuminate\Http\JsonResponse Retorna uma resposta JSON indicando o sucesso da exclusão.
     */
    public function destroy($uuid)
    {
        // Busca a entidade no banco de dados pelo UUID
        // Lança uma exceção automaticamente se o UUID não for encontrado
        $entidade = Entidade::findOrFail($uuid);

        // Remove a entidade do banco de dados
        $entidade->delete();

        // Retorna uma resposta JSON indicando o sucesso da operação
        return response()->json([
            'message' => 'Entidade excluída com sucesso!' // Mensagem de confirmação
        ]);
    }
}
