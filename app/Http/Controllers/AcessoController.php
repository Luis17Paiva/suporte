<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use Illuminate\Support\Facades\DB;
use App\Models\AcessoHist;
use Illuminate\Http\Request;

class AcessoController extends Controller
{  
    protected $acesso;
    protected $request;
    public function __construct(Acesso $acesso, Request $request)
    {
        $this->acesso = $acesso;
        $this->request = $request;
    } 
    public function index()
    {
        $acessos = Acesso::all();

        return view('acessos.acesso', compact('acessos'));
    }

    public function create()
    {
        return view('acessos.create');
    }

    public function store()
    {
        $acesso = new Acesso;
        $acesso->empresa = $this->request->input('empresa');
        $acesso->tipo_acesso = $this->request->input('tipo_acesso');
        $acesso->acesso_id = $this->request->input('acesso_id');
        $acesso->senha =  $this->request->input('senha');
        $acesso->save();

        return redirect()->route('acessos')->with('success', 'Acesso adicionado com sucesso.');
    }

    public function edit($id)
    {
        $acesso = Acesso::find($id);

        return view('acessos.edit', compact('acesso'));
    }

    public function update($id)
    {

        $acesso = Acesso::findOrFail($id);
        $acesso->empresa =  $this->request->input('empresa');
        $acesso->tipo_acesso =  $this->request->input('tipo_acesso');
        $acesso->acesso_id =  $this->request->input('acesso_id');
        $acesso->senha =  $this->request->input('senha');
        $acesso->save();

        return redirect()->route('acessos');
    }

    public function HistoricoAcessos($acessoId)
    {   
    
        $startDate = $this->request->input('startDate');
        $endDate =  $this->request->input('endDate');
    
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


    public function registrarAcesso($acessoId)
    {
        $usuario = $this->request->user()->name;

        // Crie uma instância do modelo e preencha os dados
        $acessoHist = new AcessoHist([
            'usuario' => $usuario,
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