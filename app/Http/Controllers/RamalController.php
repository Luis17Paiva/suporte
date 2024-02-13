<?php

namespace App\Http\Controllers;

use App\Models\Ramal;
use Illuminate\Http\Request;

class RamalController extends Controller
{
    protected $ramal;
    protected $request;
    public function __construct(Ramal $ramal, Request $request)
    {
        $this->ramal = $ramal;
        $this->request = $request;
    }
    public function index()
    {
        $ramais = Ramal::all();
        return view('ramais/ramais', compact('ramais'));
    }

    public function create()
    {
        return view('ramais/create');
    }

    public function store()
    {
        $ramal = new Ramal;
        $ramal->id =$this->request->input('ramal');
        $ramal->nome =$this->request->input('nome');
        $ramal->save();

        return redirect()->route('ramal')->with('success', 'Ramal criado com sucesso.');
    }

    public function edit($id)
    {
        $ramal = Ramal::findOrFail($id);
        return view('ramais.edit', compact('ramal'));
    }

    public function update($id)
    {
        $ramal = Ramal::findOrFail($id);
        $ramal->id = $this->request->input('ramal');
        $ramal->nome = $this->request->input('nome');
        $ramal->excluido = $this->request->has('excluido');
        $ramal->save();

        return redirect()->route('ramal')->with('success', 'Ramal atualizado com sucesso.');
    }


}
