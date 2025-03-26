<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{

    protected $table = 'especialidades';
    
    protected $primaryKey = 'uuid';
    public $incrementing = false; // Caso use UUID
    protected $keyType = 'string'; // Caso use UUID

    protected $fillable = [
        'uuid',
        'label',
    ];

    public function entidades()
    {
        return $this->belongsToMany(Entidade::class, 'entidade_especialidades', 'especialidade_uuid', 'entidade_uuid');
    }
}