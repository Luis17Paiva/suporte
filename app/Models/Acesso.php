<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{
    use HasFactory;
    protected $table = 'acesso'; 
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'empresa',
        'tipo_acesso',
        'acesso_id',
        'senha',
        'excluido'
    ];
}

