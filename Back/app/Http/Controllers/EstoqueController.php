<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    protected $estoque;

    public function __construct(Estoque $estoque)
    {
        $this->estoque = $estoque;
    }

    public function index()
    {
        $estoques = Estoque::with('item:id,nome,descricao,codigo')->get();

        $estoquesAgrupados = $estoques->groupBy(function ($estoque) {
            return $estoque->item->nome ?? 'Sem Nome';
        })->map(function ($grupo, $nome) {
            $quantidadeTotal = $grupo->sum('quantidade');
            $item = $grupo->first()->item;

            return [
                'nome'       => $nome,
                'descricao'  => $item->descricao ?? null,
                'codigo'     => $item->codigo ?? null,
                'estoque'    => [
                    'quantidade' => $quantidadeTotal,
                ],
                'id' => $grupo->first()->id,
            ];
        })->values();

        return response()->json($estoquesAgrupados);
    }

}