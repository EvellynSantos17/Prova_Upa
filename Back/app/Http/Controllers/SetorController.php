<?php

namespace App\Http\Controllers;

use App\Models\Setor;

class SetorController extends Controller
{
    private Setor $setor;

    /**
     * SetorController constructor.
     * @param Setor $setor
     */
    public function __construct(Setor $setor)
    {
        $this->setor = $setor;
    }

    /**
     * Método para listar todos os setores
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $setores = $this->setor
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($setores);
    }
}
