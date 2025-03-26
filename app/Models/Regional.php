<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{

    protected $table = 'regionais';
    
    protected $primaryKey = 'uuid';
    public $incrementing = false; // Caso use UUID
    protected $keyType = 'string'; // Caso use UUID

    protected $fillable = [
        'uuid',
        'label',
    ];
}