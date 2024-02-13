<?php

namespace App\Http\Controllers;

use App\Models\Acesso;
use Illuminate\Support\Facades\DB;
use App\Models\AcessoHist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcessoController extends Controller
{
    protected $acesso;
    protected $request;
    public function __construct(Acesso $acesso, Request $request)
    {
        $this->middleware('auth');
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
        $acesso->id_cliente = $this->request->input('id_cliente');
        $acesso->acesso_tipo = $this->request->input('acesso_tipo');
        $acesso->acesso_id = $this->request->input('acesso_id');
        $acesso->acesso_pass =  $this->request->input('acesso_pass');
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
        $acesso->id_cliente = $this->request->input('id_cliente');
        $acesso->acesso_tipo = $this->request->input('acesso_tipo');
        $acesso->acesso_id = $this->request->input('acesso_id');
        $acesso->acesso_pass =  $this->request->input('acesso_pass');
        $acesso->save();

        return redirect()->route('acessos');
    }

    public function HistoricoAcessos($acessoId)
    {
        $validator = Validator::make($this->request->all(), [
            'acessoId' => 'required|integer|min:1',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $startDate = $this->request->input('startDate');
        $endDate = $this->request->input('endDate');

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
        $id_user = $this->request->user()->id;

        $validator = Validator::make($this->request->all(), [
            'acessoId' => 'required|integer|min:1',
            'id_user' => ['required','integer']
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        // Crie uma instância do modelo e preencha os dados
        $acessoHist = new AcessoHist([
            'id_user' => $id_user,
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
