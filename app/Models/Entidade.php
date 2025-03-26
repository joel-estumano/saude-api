<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; 

class Entidade extends Model
{
    protected $table = 'entidades';
    
    protected $primaryKey = 'uuid';
    public $incrementing = false; // Caso use UUID
    protected $keyType = 'string'; // Caso use UUID

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'regional_id',
        'data_inauguracao',
        'ativa',
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'entidade_especialidades', 'entidade_uuid', 'especialidade_uuid');
    }
}