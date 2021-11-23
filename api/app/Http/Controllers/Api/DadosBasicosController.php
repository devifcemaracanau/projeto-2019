<?php

namespace App\Http\Controllers\Api;

use App\Services\DadosBasicos;
use Illuminate\Http\JsonResponse;

class DadosBasicosController extends Controller
{

    public function index(DadosBasicos $dadosBasicosBusiness): JsonResponse
    {
        return response()->json($dadosBasicosBusiness->getAll());
    }
}
