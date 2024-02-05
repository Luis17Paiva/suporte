<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    protected $colaborador;
    protected $request;
    public function __construct(Colaborador $colaborador, Request $request)
    {
        $this->colaborador = $colaborador;
        $this->request = $request;
    }
    public function index()
    {
        $colaboradores = Colaborador::all();
        return view('colaboradores.colaboradores', compact('colaboradores'));
    }

    public function create()
    {
        return view('colaboradores.create');
    }

    public function store()
    {
        $colaborador = new Colaborador;
        $colaborador->id =$this->request->input('ramal');
        $colaborador->nome =$this->request->input('nome');
        $colaborador->save();

        return redirect()->route('colaboradores')->with('success', 'Colaborador criado com sucesso.');
    }

    public function edit($id)
    {
        $colaborador = Colaborador::findOrFail($id);
        return view('colaboradores.edit', compact('colaborador'));
    }

    public function update($id)
    {
        $colaborador = Colaborador::findOrFail($id);
        $colaborador->id = $this->request->input('ramal');
        $colaborador->nome = $this->request->input('nome');
        $colaborador->excluido = $this->request->has('excluido');
        $colaborador->save();

        return redirect()->route('colaboradores')->with('success', 'Colaborador atualizado com sucesso.');
    }


}
