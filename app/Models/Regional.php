<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe Regional que representa uma unidade regional associada a entidades.
 */
class Regional extends Model
{
    // Define a tabela associada a este modelo
    protected $table = 'regionais';
    
    // Define a chave primária como 'uuid' em vez do padrão 'id'
    protected $primaryKey = 'uuid';

    // Indica que a chave primária não é incrementável (usada quando a chave primária é um UUID)
    public $incrementing = false;

    // Define o tipo da chave primária como string (usada quando a chave primária é um UUID)
    protected $keyType = 'string';

    // Define os campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'uuid', // Identificador único da regional
        'label', // Nome ou descrição da regional
    ];
}