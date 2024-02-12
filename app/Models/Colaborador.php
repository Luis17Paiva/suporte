<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;
    protected $table = 'colaborador';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'id_ramal',
        'excluido'
    ];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'id_colaborador');
    }
}
