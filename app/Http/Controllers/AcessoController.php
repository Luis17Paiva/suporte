<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use Illuminate\Http\Request;

class AcessoController extends Controller
{
    public function index()
    {
        $acessos = Acesso::all();

        return view('Acessos.acesso', compact('acessos'));
    }

    public function create()
    {
        return view('Acessos.create');
    }

    public function store(Request $request)
    {
        $acesso = new Acesso;
        $acesso->empresa = $request->input('empresa');
        $acesso->tipo_acesso = $request->input('tipo_acesso');
        $acesso->acesso_id = $request->input('acesso_id');
        $acesso->senha = $request->input('senha');
        $acesso->save();

        return redirect()->route('acessos')->with('success', 'Acesso adicionado com sucesso.');
    }

    public function edit($id)
    {
        $acesso = Acesso::find($id);

        return view('Acessos.edit', compact('acesso'));
    }

    public function update(Request $request, $id)
    {

        $acesso = Acesso::findOrFail($id);
        $acesso->empresa = $request->input('empresa');
        $acesso->tipo_acesso = $request->input('tipo_acesso');
        $acesso->acesso_id = $request->input('acesso_id');
        $acesso->senha = $request->input('senha');
        $acesso->save();

        return redirect()->route('Acessos.acesso');
    }

    public function destroy($id)
    {
        $acesso = Acesso::findOrFail($id);
        $acesso->excluido  = true;
        $acesso->save();

        return redirect()->route('Acessos.acesso');
    }
}