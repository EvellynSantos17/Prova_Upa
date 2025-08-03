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
          if ($itemPorNome) {

               $novoItem = $this->itens->create($validatedData);
               $novoItem->estoque()->create(['quantidade' => 1]);

               return response()->json([
                    'message' => 'Novo item criado com nome já existente. Estoque iniciado com 1.',
                    'data' => $novoItem->load('estoque')
               ], 201);
          }

          $item = $this->itens->create($validatedData);
          $item->estoque()->create(['quantidade' => 1]);

          return response()->json([
               'message' => 'Item e estoque criados com sucesso.',
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
