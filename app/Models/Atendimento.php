<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    use HasFactory;

    protected $table = 'atendimento'; // Nome da tabela no banco de dados
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
        'id_ramal',
        'id_asterisk',
        'excluido'
    ];

    public function ramal()
    {
        return $this->belongsTo(Ramal::class, 'id_ramal');
    }
};
