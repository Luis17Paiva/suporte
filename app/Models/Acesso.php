<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{
    use HasFactory;
    protected $table = 'acesso'; 
    protected $primaryKey = 'id'; 
    public $timestamps = true;
    protected $fillable = [
        'id_cliente',
        'acesso_tipo',
        'acesso_id',
        'acesso_pass',
        'excluido'
    ];
    protected $casts = [
        'excluido' => 'boolean'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function acessoHist()
    {
        return $this->hasMany(AcessoHist::class, 'id_acesso');
    }
}

