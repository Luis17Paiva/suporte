<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcessoHist extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'acesso_hist'; 
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'id_acesso', 
        'id_user',
        'excluido'
    ]; 
    protected $casts = [
        'excluido' => 'boolean'
    ];

    public function acesso()
    {
        return $this->belongsTo(Acesso::class, 'id_acesso');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
