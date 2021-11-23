<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\InstituicaoResource;
use App\Http\Resources\ProfissionalResource;
use App\Services\InstituicaoService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InstituicoesController extends Controller
{
    protected InstituicaoService $instituicaoService;

    public function __construct(InstituicaoService $instituicaoBusiness)
    {
        $this->instituicaoService = $instituicaoBusiness;
    }

    /**
     * End-point para busca de todas as instituições
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        // Apenas filtros permitidos
        $allowedParams = ['uf', 'municipio', 'bairro', 'tipo_instituicao'];
        $filters = array_filter($request->all(), function($param) use ($allowedParams) {
            return in_array($param, $allowedParams);
        }, ARRAY_FILTER_USE_KEY);

        return InstituicaoResource::collection(
            $this->instituicaoService->getAll($filters)
        );
    }

    /**
     * End-point para de municípios de um estado
     * @param $id_instituicao
     * @return AnonymousResourceCollection
     */
    public function getProfissionais($id_instituicao): AnonymousResourceCollection
    {
        return ProfissionalResource::collection(
            $this->instituicaoService->getProfissionais($id_instituicao)
        );
    }
}
