<?php

namespace App\Http\Controllers;

use App\Models\Setor;

class SetorController extends Controller
{
    private Setor $setor;

    public function __construct(Setor $setor)
    {
        $this->setor = $setor;
    }

    public function index()
    {
        $setores = $this->setor
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($setores);
    }
}
