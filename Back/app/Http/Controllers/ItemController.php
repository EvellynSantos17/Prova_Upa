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
    public function lista()
    {
        try {
            $itens = $this->itens->get();

            return response()->json($itens);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao listar itens.',
                'detalhes' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'nullable|string|max:1000',
            ]);

            $item = $this->itens->find($id);
            if (!$item) {
                return response()->json(['error' => 'Item não encontrado'], 404);
            }

            $item->update([
                'nome' => $validatedData['nome'],
                'descricao' => $validatedData['descricao'] ?? null,
            ]);

            return response()->json([
                'message' => 'Item atualizado.',
                'data' => $item
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar item.',
                'detalhes' => $e->getMessage()
            ], 500);
        }
    }

    public function getItemPorCodigo($codigo)
    {
        $item = $this->itens->where('codigo', $codigo)->first();

        if (!$item) {
            return response()->json(['message' => 'Item não encontrado'], 404);
        }

        return response()->json($item);
    }

}