<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;
    protected $table = 'colaborador';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'excluido',
    ];
}
