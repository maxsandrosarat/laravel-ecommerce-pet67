<?php

namespace App\Http\Controllers;

use App\CupomDesconto;
use Illuminate\Http\Request;

class CupomDescontoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $cupons = CupomDesconto::all();
        return view('cadastros.cupons',compact('cupons'));
    }

    public function store(Request $request)
    {
        $cupom = new CupomDesconto();
        $cupom->nome = $request->input('nome');
        $cupom->localizador = $request->input('localizador');
        $cupom->desconto = $request->input('desconto');
        $cupom->modo_desconto = $request->input('modo_desconto');
        $cupom->limite = $request->input('limite');
        $cupom->modo_limite = $request->input('modo_limite');
        $cupom->validade = $request->input('validade');
        $cupom->ativo = $request->input('ativo');
        $cupom->save();
        return redirect('/admin/cuponsDesconto');
    }

    public function update(Request $request, $id)
    {
        $cupom = CupomDesconto::find($id);
        if($request->input('nome')!=""){
            $cupom->nome = $request->input('nome');
        }
        if($request->input('localizador')!=""){
            $cupom->localizador = $request->input('localizador');
        }
        if($request->input('desconto')!=""){
            $cupom->desconto = $request->input('desconto');
        }
        if($request->input('modo_desconto')!=""){
            $cupom->modo_desconto = $request->input('modo_desconto');
        }
        if($request->input('limite')!=""){
            $cupom->limite = $request->input('limite');
        }
        if($request->input('modo_limite')!="")
        {
            $cupom->modo_limite = $request->input('modo_limite');
        }
        if($request->input('validade')!=""){
            $cupom->validade = $request->input('validade');
        }
        if($request->input('ativo')!=""){
            $cupom->ativo = $request->input('ativo');
        }
        $cupom->save();
        return redirect('/admin/cuponsDesconto');
    }

    public function destroy($id)
    {
        $cupom = Anuncio::find($id);
        if(isset($ancupomuncio)){
            $cupom->delete();
        }
        return redirect('/admin/cuponsDesconto');
    }
}
