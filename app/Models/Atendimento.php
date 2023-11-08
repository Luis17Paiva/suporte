<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    use HasFactory;

    protected $table = 'atendimentos'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da coluna de chave primária

    protected $fillable = [
        'hash',
        'numero',
        'ura',
        'hora_chamada',
        'hora_atendimento',
        'hora_desliga',
        'id_ramal',
        'status',
        'data_inclusao',
        'id_asterisk'
    ];
}
