<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    public $timestamps = true;

    protected $fillable = [
        'nome', 
        'excluido'
    ];

    protected $casts = [
        'excluido' => 'boolean'
    ];

    public function contatos()
    {
        return $this->hasMany(ClienteContato::class, 'id_cliente');
    }
}
