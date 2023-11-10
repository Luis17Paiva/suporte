<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{
    use HasFactory;
    protected $table = 'acesso'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da coluna de chave primária
    protected $fillable = [
        'empresa',
        'tipo_acesso',
        'acesso_id',
        'senha',
        'excluido'
        // Adicione outros campos aqui
    ];
}

