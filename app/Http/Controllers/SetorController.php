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

    /*
        * Display a listing of the setores.
        * @return \Illuminate\Http\JsonResponse
    */
    public function index()
    {
        $setores = $this->setor->get();
        return response()->json($setores, 200);
    }
}
