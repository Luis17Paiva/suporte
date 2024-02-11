<?php

namespace App\Http\Controllers;
use App\Models\Atendimento;

use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    protected $atendimento;
    protected $request;
    public function __construct(Atendimento $atendimento, Request $request)
    {
        $this->colaborador = $atendimento;
        $this->request = $request;
    }

    public function index()
    {
        return view("Atendimentos/atendimentos");
    }



}
