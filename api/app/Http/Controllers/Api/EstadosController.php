<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EstadoResource;
use App\Http\Resources\MunicipioResource;
use App\Services\EstadoService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EstadosController extends Controller
{
    protected EstadoService $estadoService;

    public function __construct(EstadoService $estadoService)
    {
        $this->estadoService = $estadoService;
    }

    /**
     * End-point para busca de todos os estados
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $estados = $this->estadoService->getAll();

        return EstadoResource::collection($estados);
    }

    /**
     * End-point para de municípios de um estado
     * @param int $id_estado
     * @return AnonymousResourceCollection
     */
    public function getMunicipios(int $id_estado): AnonymousResourceCollection
    {
        return MunicipioResource::collection($this->estadoService->getMunicipios($id_estado));
    }
}
