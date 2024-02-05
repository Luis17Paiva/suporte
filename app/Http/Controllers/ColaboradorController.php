<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    public function index()
    {
        $colaboradores = Colaborador::all();
        return view('colaboradores.colaboradores', compact('colaboradores'));
    }

    public function create()
    {
        return view('colaboradores.create');
    }

    public function store(Request $request)
    {
        $colaborador = new Colaborador;
        $colaborador->id = $request->input('ramal');
        $colaborador->nome = $request->input('nome');
        $colaborador->save();

        return redirect()->route('colaboradores')->with('success', 'Colaborador criado com sucesso.');
    }

    public function edit($id)
    {
        $colaborador = Colaborador::findOrFail($id);
        return view('colaboradores.edit', compact('colaborador'));
    }

    public function update(Request $request, $id)
    {
        $colaborador = Colaborador::findOrFail($id);
        $colaborador->id = $request->input('ramal');
        $colaborador->nome = $request->input('nome');
        $colaborador->excluido = $request->has('excluido');
        $colaborador->save();

        return redirect()->route('colaboradores')->with('success', 'Colaborador atualizado com sucesso.');
    }


}
