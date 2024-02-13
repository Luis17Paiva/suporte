<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ramal extends Model
{
    use HasFactory;
    protected $table = 'ramal';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nome',
        'ramal',
        'excluido'
    ];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'ramal');
    }
}
