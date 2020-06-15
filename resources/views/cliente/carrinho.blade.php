@extends('layouts.app_cliente', ["current"=>"carrinho"])

@section('body')

<div class="container">
    <h3>Produtos no carrinho</h3>
    <div scope="row">
        @if (Session::has('mensagem-sucesso'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{ Session::get('mensagem-sucesso') }}</strong>
            </div>
        @endif
        @if (Session::has('mensagem-falha'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{ Session::get('mensagem-falha') }}</strong>
            </div>
        @endif
        @forelse ($pedidos as $pedido)
            <div>
                <h5 scope="col"> Pedido: {{ $pedido->id }} </h5>
                <h5 scope="col"> Criado em: {{ $pedido->created_at->format('d/m/Y H:i') }} </h5>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Qtd</th>
                        <th>Produto</th>
                        <th>Valor Unit.</th>
                        <th>Desconto(s)</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_pedido = 0;
                    @endphp
                    @foreach ($pedido->pedido_produtos as $pedido_produto)

                    <tr>
                        <td>
                            <img width="100" height="100" src="/storage/{{ $pedido_produto->produto->foto }}">
                        </td>
                        <td class="center-align" width="160">
                            <div class="center-align">
                                @if($pedido_produto->produto->granel=="1")
                                    <input name="qtd" id="qtd" onblur="granel({{ $pedido_produto->produto_id }})" @if($pedido_produto->qtdGranel>0) value="{{ number_format($pedido_produto->qtdGranel, 1, ',', '.') }}" @else placeholder="Exemplo: 0,5" @endif>
                                    <br/>
                                @else
                                <a class="col l4 m4 s4" href="#" onclick="carrinhoRemoverProduto({{ $pedido->id }}, {{ $pedido_produto->produto_id }}, 1 )">
                                    <i class="material-icons small">remove_circle_outline</i>
                                </a>
                                <span class="col l4 m4 s4"> {{ $pedido_produto->qtd }} </span>
                                <a class="col l4 m4 s4" href="#" onclick="carrinhoAdicionarProduto({{ $pedido_produto->produto_id }})">
                                    <i class="material-icons small">add_circle_outline</i>
                                </a>
                                @endif
                            </div>
                            <a href="#" style="text-decoration:none;"onclick="carrinhoRemoverProduto({{ $pedido->id }}, {{ $pedido_produto->produto_id }}, 0)" data-toggle="tooltip" data-placement="top" title="Remover produto do carrinho?">Remover Produto</a>
                        </td>
                        <td> {{ $pedido_produto->produto->nome }} {{$pedido_produto->produto->tipo_animal->nome}} @if($pedido_produto->produto->tipo_fase=='filhote') Filhote @else @if($pedido_produto->produto->tipo_fase=='adulto') Adulto @else @if($pedido_produto->produto->tipo_fase=='castrado') Castrado @endif @endif @endif {{$pedido_produto->produto->marca->nome}} @if($pedido_produto->produto->embalagem!="Unidade") {{$pedido_produto->produto->embalagem}} @endif</td>
                        <td>R$ {{ number_format($pedido_produto->produto->preco, 2, ',', '.') }} <input type="hidden" name="preco" id="preco" value="{{$pedido_produto->produto->preco}}"></td>
                        <td>R$ {{ number_format($pedido_produto->descontos, 2, ',', '.') }}</td>
                        @php
                            $total_produto = $pedido_produto->valores - $pedido_produto->descontos;
                            $total_pedido += $total_produto;
                        @endphp
                        @if($pedido_produto->produto->granel=="1")
                        <td><input name="total" readonly id="total" value="R$ {{ number_format($total_produto, 2, ',', '.') }}"/></td>
                        @else
                        <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div scope="row">
                <strong >Total do pedido: </strong>
                <span scope="col">R$ {{ number_format($total_pedido, 2, ',', '.') }}</span>
            </div>
            <div scope="row">
                <form method="POST" action="{{ route('carrinho.desconto') }}">
                    @csrf
                    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
                    <strong >Cupom de desconto: </strong>
                    <input  type="text" name="cupom">
                    <button type="submit" class="btn btn-primary">Validar</button>
                </form>
            </div>
            <br/>
            <div>
                <a type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Voltar a página inicial para continuar comprando?" href="{{ route('index') }}">Continuar comprando</a>
                <br/><br/>
                @if(count($enderecos)==0)
                <h5>Nenhum Endereço Cadastrado</h5>
                <a class="btn btn-primary" href="/enderecos">Cadastrar Endereço</a>
                @else
                <form method="POST" action="{{ route('carrinho.concluir') }}">
                    @csrf
                    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
                    <h6>Selecione endereço para entrega:</h6>
                    @foreach ($enderecos as $endereco)
                    <input type="radio" id="endereco{{$endereco->id}}" name="endereco" value="{{$endereco->id}}" required>
                    <label for="endereco{{$endereco->id}}">{{$endereco->rua}}, {{$endereco->numero}} ({{$endereco->complemento}}) - {{$endereco->bairro}} - {{$endereco->cidade}} - {{$endereco->uf}}</label>
                    @endforeach
                    <br/>
                    <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Adquirir os produtos concluindo a compra?">
                        Concluir compra
                    </button>
                </form>
                @endif
            </div>
        @empty
            <h5>Não há nenhum produto no carrinho</h5>
            <a type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Voltar a página inicial para continuar comprando?" href="{{ route('index') }}">Adicionar Produto</a>
        @endforelse
    </div>
</div>

<form id="form-remover-produto" method="POST" action="{{ route('carrinho.remover') }}">
    @csrf
    {{ method_field('DELETE') }}
    <input type="hidden" name="pedido_id">
    <input type="hidden" name="produto_id">
    <input type="hidden" name="item">
</form>
<form id="form-adicionar-produto" method="POST" action="{{ route('carrinho.adicionar') }}">
    @csrf
    <input type="hidden" name="id">
</form>
<form id="form-adicionar-produto-granel" method="POST" action="{{ route('carrinho.adicionarGranel') }}">
    @csrf
    <input type="hidden" name="id">
    <input type="hidden" name="qtd">
    <input type="hidden" name="preco">
</form>

@endsection