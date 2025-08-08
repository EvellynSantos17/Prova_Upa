<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Itens;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    protected $estoque;
    protected $item;

    public function __construct(Estoque $estoque , Itens $item)
    {
        $this->estoque = $estoque;
        $this->item = $item;
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

   

  public function destroy($id)
{
    $item = $this->item->find($id);

    if (!$item) {
        return response()->json(['error' => 'Item não encontrado'], 404);
    }

    $itensComMesmoNome = $this->item->where('nome', $item->nome)->get();

    if ($itensComMesmoNome->isEmpty()) {
        return response()->json(['error' => 'Nenhum outro item com mesmo nome encontrado'], 404);
    }

    $estoques = Estoque::whereIn('item_id', $itensComMesmoNome->pluck('id'))->get();

    if ($estoques->isEmpty()) {
        return response()->json(['error' => 'Nenhum estoque encontrado para os itens com mesmo nome'], 404);
    }

    $estoqueParaAlterar = $estoques->sortByDesc('quantidade')->first();

    if ($estoqueParaAlterar->quantidade > 0) {
        $estoqueParaAlterar->quantidade -= 1;
        $estoqueParaAlterar->save();
    }

    if ($estoqueParaAlterar->quantidade <= 0) {
        $estoqueParaAlterar->delete();
    }

    $item->delete();

    return response()->json(['message' => 'Item deletado e estoque atualizado com sucesso.']);
}




}