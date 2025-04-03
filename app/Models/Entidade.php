<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Classe Entidade que representa uma unidade principal associada a especialidades e regionais.
 */
class Entidade extends Model
{
    /** 
     * Nome da tabela no banco de dados.
     */
    protected $table = 'entidades';

    /** 
     * Configurações relacionadas à chave primária.
     * - Define 'uuid' como a chave primária.
     * - Indica que a chave não é incrementável.
     * - Define o tipo de chave primária como string (usando UUID).
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false; // Usando UUID como chave primária
    protected $keyType = 'string'; // Define UUID como string

    /**
     * Lista de atributos que podem ser preenchidos em massa (mass assignment).
     * Esses atributos permitem a atribuição direta durante a criação ou atualização do modelo.
     */
    protected $fillable = [
        'razao_social',    // Razão social da entidade
        'nome_fantasia',   // Nome fantasia da entidade
        'cnpj',            // Número de CNPJ da entidade
        'regional_id',     // Identificador único da regional associada
        'data_inauguracao', // Data de inauguração da entidade
        'ativa',           // Indica se a entidade está ativa (booleano)
    ];

    /**
     * Define o relacionamento "muitos-para-um" entre Entidade e Regional.
     * Cada entidade pertence a uma regional específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo Relacionamento com o modelo Regional.
     */
    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    /**
     * Configuração do evento de criação do modelo para gerar automaticamente o UUID, 
     * caso ainda não tenha sido definido.
     * Utiliza o pacote `Uuid` para gerar identificadores únicos.
     */
    protected static function boot()
    {
        parent::boot();

        // Antes de criar, verifica se o UUID está vazio e o gera automaticamente.
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Uuid::uuid4()->toString(); // Gera um UUID único
            }
        });
    }

    /**
     * Define o relacionamento "muitos-para-muitos" entre Entidade e Especialidade.
     * Este relacionamento utiliza a tabela pivô 'entidade_especialidades' para vincular os modelos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany Relacionamento com o modelo Especialidade.
     */
    public function especialidades()
    {
        return $this->belongsToMany(
            Especialidade::class,        // Modelo relacionado
            'entidade_especialidades',   // Tabela intermediária
            'entidade_uuid',             // Chave estrangeira referente à Entidade
            'especialidade_uuid'         // Chave estrangeira referente à Especialidade
        );
    }
}