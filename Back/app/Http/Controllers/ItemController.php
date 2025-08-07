<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItensRequest;
use App\Models\Itens;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itens;

    public function __construct(Itens $itens)
    {
        $this->itens = $itens;
    }

 public function store(ItensRequest $request)
{
    try {
        $validatedData = $request->validated();
        $quantidadeNova = $request->input('quantidade', 1);

        $itemPorCodigo = $this->itens->where('codigo', $validatedData['codigo'])->first();
        if ($itemPorCodigo) {
            if ($itemPorCodigo->nome === $validatedData['nome']) {
                return response()->json([
                    'message' => 'Item já existe com esse nome e código.',
                    'data' => $itemPorCodigo->load('estoque')
                ]);
            }
            return response()->json([
                'error' => 'O código já está em uso por outro item.',
            ], 422);
        }

        $itemPorNome = $this->itens->where('nome', $validatedData['nome'])->first();

        $item = $this->itens->create($validatedData);

        $item->estoque()->create(['quantidade' => 0]);

        if ($itemPorNome) {
            $estoqueExistente = $itemPorNome->estoque()->first();

            if ($estoqueExistente) {
                $estoqueExistente->quantidade += $quantidadeNova;
                $estoqueExistente->save();
            } else {
                $itemPorNome->estoque()->create(['quantidade' => $quantidadeNova]);
            }
        } else {
            $estoqueNovoItem = $item->estoque()->first();
            $estoqueNovoItem->quantidade += $quantidadeNova;
            $estoqueNovoItem->save();
        }

        return response()->json([
            'message' => 'Item criado.',
            'data' => $item->load('estoque')
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro ao criar item.',
            'detalhes' => $e->getMessage()
        ], 500);
    }
}

}