<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligacao extends Model
{
    use HasFactory;

    protected $table = 'ligacao'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Nome da coluna de chave primária

    public $timestamps = true;

    protected $fillable = [
        'hash',
        'numero',
        'ura',
        'status',
        'hora_chamada',
        'hora_atendimento',
        'hora_desliga',
        'ramal',
        'id_asterisk',
        'excluido'
    ];

};
