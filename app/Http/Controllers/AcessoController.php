<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use Illuminate\Support\Facades\DB;
use App\Models\AcessoHist;
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

    public function HistoricoAcessos(Request $request, $acessoId)
    {   
    
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
    
        // Verificar se o acessoId é um número inteiro válido
        if (!is_numeric($acessoId) || $acessoId <= 0) {
            return response()->json(['message' => 'Acesso ID inválido']);
        }
    
        $data = DB::table('acesso_hist')
            ->where('acesso_id', $acessoId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->toArray(); // Convertendo a coleção para array

        if (empty($data)) {
            return response()->json(['message' => 'Nenhum dado encontrado']);
        } else {
            return response()->json($data);
        }
    }


    public function registrarAcesso(Request $request, $acessoId)
    {
        $usuario = $request->user()->name;
        $dataAcesso = now();

        // Crie uma instância do modelo e preencha os dados
        $acessoHist = new AcessoHist([
            'usuario' => $usuario,
            'data_acesso' => $dataAcesso,
            'acesso_id' => $acessoId,
        ]);

        // Salve os dados no banco de dados
        $acessoHist->save();

        return response()->json(['message' => 'Acesso registrado com sucesso']);
    }

    public function destroy($id)
    {
        $acesso = Acesso::findOrFail($id);
        $acesso->excluido = true;
        $acesso->save();

        return redirect()->route('acessos');
    }
}