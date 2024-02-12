<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteContato extends Model
{
    use HasFactory;

    protected $table = 'cliente_contato';
    public $timestamps = true;

    protected $fillable = [
        'id_cliente', 
        'numero', 
        'email'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
};
