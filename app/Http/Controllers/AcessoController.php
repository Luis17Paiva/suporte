<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use App\Models\HistoricoAcesso;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return redirect()->route('acessos');
    }

    public function ShowHist(Request $request)
    {
        $acessoId = $request->input('acessoId');
        $dataInicial = $request->input('data_inicial');
        $dataFinal = $request->input('data_final');

        $historicoAcessos = Acesso::where('acesso_id', $acessoId)
            ->whereDate('data_acesso', '>=', $dataInicial)
            ->whereDate('data_acesso', '<=', $dataFinal)
            ->get();

        
        if ($historicoAcessos->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum histórico de acesso encontrado para este ID de acesso e intervalo de datas.',
            ], 404);
        }

        $data = array( 
            'historicoAcessos' => $historicoAcessos
        );

        return json_encode($data);
      
    }

    public function destroy($id)
    {
        $acesso = Acesso::findOrFail($id);
        $acesso->excluido = true;
        $acesso->save();

        return redirect()->route('acessos');
    }
}