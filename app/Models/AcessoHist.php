<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcessoHist extends Model
{
    use HasFactory;

    protected $timestamps = true;
    protected $table = 'acesso_hist'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'usuario', 
        'data_acesso',
        'acesso_id'
    ]; 
  
}
