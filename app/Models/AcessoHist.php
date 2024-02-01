<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcessoHist extends Model
{
    use HasFactory;

    // Defina os timestamps como públicos
    public $timestamps = true;
    protected $table = 'acesso_hist'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'usuario', 
        'acesso_id'
    ]; 
  
}
