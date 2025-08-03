<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItensRequest;
use App\Models\Itens;
use Illuminate\Http\Request;

class ItemController extends Controller
{
     protected $itens;

     public function __construct(Itens $itens){
        $this->itens = $itens;
     }
    public function store(ItensRequest $request)
    {
       try{
            $validatedData = $request->validated();
            $itens = $this->itens->create($validatedData);
            return response()->json(['message' => 'Item created successfully', 'data' => $itens], 201);
       }catch (\Exception $e) {
              return response()->json(['error' => 'An error occurred while creating the item.'], 500);
         }
    }
}
