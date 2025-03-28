<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Entidade extends Model
{
    /**
     * Nome da tabela no banco de dados.
     */
    protected $table = 'entidades';

    /**
     * Configurações da chave primária.
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false; // Usando UUID como chave primária
    protected $keyType = 'string'; // Define UUID como string

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'regional_id',
        'data_inauguracao',
        'ativa',
    ];

    /**
     * Relacionamento com a tabela 'regionais'.
     */
    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    /**
     * Configuração do evento de criação do modelo para gerar UUID automaticamente.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    /**
     * Relacionamento com a tabela 'especialidades' por meio da tabela pivô 'entidade_especialidades'.
     */
    public function especialidades()
    {
        return $this->belongsToMany(
            Especialidade::class, 
            'entidade_especialidades', 
            'entidade_uuid', 
            'especialidade_uuid'
        );
    }
}