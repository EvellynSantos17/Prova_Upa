<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Item;
use App\Models\Estoque;
use App\Models\Itens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimentacaoController extends Controller
{
    protected $movimentacao;
    protected $item;

    public function __construct(Movimentacao $movimentacao, Itens $item)
    {
        $this->movimentacao = $movimentacao;
        $this->item = $item;
    }


   public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:itens,id',
            'tipo' => 'required|in:retirada,devolucao',
            'motivo_retirada' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        $item = $this->item::findOrFail($validated['item_id']);
        $itensComMesmoNome = $this->item::where('nome', $item->nome)->pluck('id');

        if ($validated['tipo'] === 'retirada') {
            $estoques = Estoque::whereIn('item_id', $itensComMesmoNome)->get();
            $estoqueAtualizado = false;

            foreach ($estoques as $estoque) {
                if ($estoque->quantidade > 0) {
                    $estoque->quantidade -= 1;
                    $estoque->save();
                    $estoqueAtualizado = true;
                    break;
                }
            }

            if (!$estoqueAtualizado) {
                return response()->json([
                    'message' => 'Estoque esgotado para este item.',
                ], 400);
            }
        } elseif ($validated['tipo'] === 'devolucao') {
            $estoques = Estoque::whereIn('item_id', $itensComMesmoNome)->get();

            if ($estoques->isEmpty()) {
                Estoque::create([
                    'item_id' => $item->id,
                    'quantidade' => 1,
                ]);
            } else {
                foreach ($estoques as $estoque) {
                    $estoque->quantidade += 1;
                    $estoque->save();
                    break;
                }
            }
        }

        $movimentacao = $this->movimentacao->create([
            'item_id' => $validated['item_id'],
            'user_id' => $user->id,
            'tipo' => $validated['tipo'],
            'motivo_retirada' => $validated['motivo_retirada'],
            'data_movimentacao' => now(),
        ]);

        return response()->json([
            'message' => 'Movimentação registrada com sucesso.',
            'movimentacao' => $movimentacao,
        ]);
    }
}
