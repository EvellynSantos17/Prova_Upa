<?php

namespace App\Http\Controllers;

use App\Models\Setor;

class SetorController extends Controller
{
    protected $setor;

    public function __construct(Setor $setor)
    {
        $this->setor = $setor;
    }
    public function index()
    {
        $setores = $this->setor->get();
        return response()->json($setores, 200);
    }
}
