<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\InstituicaoResource;
use App\Http\Resources\ProfissionalResource;
use App\Http\Resources\TipoInstituicaoResource;
use App\Services\InstituicaoService;
use App\Services\TiposInstituicoesService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TiposInstituicoesController extends Controller
{
    protected TiposInstituicoesService $tiposInstituicoesService;

    public function __construct(TiposInstituicoesService $tiposInstituicoesService)
    {
        $this->tiposInstituicoesService = $tiposInstituicoesService;
    }

    /**
     * End-point para busca de todas os tipos de instituições
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return TipoInstituicaoResource::collection(
            $this->tiposInstituicoesService->getAll()
        );
    }
}
