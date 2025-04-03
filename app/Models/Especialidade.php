<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe Especialidade que representa uma especialidade associada a entidades.
 */
class Especialidade extends Model
{
    // Define a tabela associada a este modelo
    protected $table = 'especialidades';
    
    // Define a chave primária como 'uuid' em vez do padrão 'id'
    protected $primaryKey = 'uuid';

    // Indica que a chave primária não é incrementável (usada quando a chave primária é um UUID)
    public $incrementing = false;

    // Define o tipo da chave primária como string (usada quando a chave primária é um UUID)
    protected $keyType = 'string';

    // Define os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'uuid', // Identificador único da especialidade
        'label', // Descrição ou nome da especialidade
    ];

    /**
     * Define o relacionamento "muitos-para-muitos" entre especialidades e entidades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany Relacionamento com a classe Entidade.
     */
    public function entidades()
    {
        return $this->belongsToMany(
            Entidade::class,          // Modelo relacionado
            'entidade_especialidades', // Tabela intermediária para o relacionamento
            'especialidade_uuid',     // Chave estrangeira na tabela intermediária referente a Especialidade
            'entidade_uuid'           // Chave estrangeira na tabela intermediária referente a Entidade
        );
    }
}