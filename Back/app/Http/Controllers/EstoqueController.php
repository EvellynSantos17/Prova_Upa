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
        $estoques = Estoque::with('item:id,nome,descricao,codigo')
            ->where('quantidade', '>', 0)
            ->get()
            ->unique(function ($estoque) {
                return $estoque->item->nome ?? null;
            });
        
        $response = $estoques->map(function ($estoque) {
            return [
                'nome'       => $estoque->item->nome ?? null,
                'descricao'  => $estoque->item->descricao ?? null,
                'codigo'     => $estoque->item->codigo ?? null,
                'estoque'    => [
                    'quantidade' => $estoque->quantidade,
                ],
                'id' => $estoque->id,
            ];
        });
    
        return response()->json($response->values());
    }

}