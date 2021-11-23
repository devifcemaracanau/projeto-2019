<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BairroResource;
use App\Http\Resources\MunicipioResource;
use App\Http\Resources\TipoInstituicaoResource;
use App\Services\MunicipioService;
use App\Http\Resources\InstituicaoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MunicipiosController extends Controller
{
    protected MunicipioService $municipioService;

    public function __construct(MunicipioService $municipioBusiness)
    {
        $this->municipioService = $municipioBusiness;
    }

    /**
     * End-point para busca de municípios
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return MunicipioResource::collection(
            $this->municipioService->getMunicipios($request->get('ids'))
        );
    }

    /**
     * End-point para busca de instituições de um município
     * @param $id_municipio
     * @return AnonymousResourceCollection
     */
    public function getInstituicoes($id_municipio): AnonymousResourceCollection
    {
        return InstituicaoResource::collection(
            $this->municipioService->getInstituicoes($id_municipio)
        );
    }

    /**
     * End-point para busca dos tipos de instituições de um município
     * @param $id_municipio
     * @return AnonymousResourceCollection
     */
    public function getTiposInstituicoes($id_municipio): AnonymousResourceCollection
    {
        return TipoInstituicaoResource::collection(
            $this->municipioService->getTiposInstituicoes($id_municipio)
        );
    }

    /**
     * End-point para busca dos bairros de um município
     * @param $id_municipio
     * @return AnonymousResourceCollection
     */
    public function getBairros($id_municipio): AnonymousResourceCollection
    {
        return BairroResource::collection(
            $this->municipioService->getBairros($id_municipio)
        );
    }

    /**
     * End-point para busca dos bairros de um município
     * @param $bairro
     * @param $id_municipio
     * @return AnonymousResourceCollection
     */
    public function getInstituicoesBairro($bairro, $id_municipio): AnonymousResourceCollection
    {
        $bairro = urldecode($bairro);

        return InstituicaoResource::collection(
            $this->municipioService->getInstituicoesBairro($id_municipio,  $bairro)
        );
    }

    /**
     * End-point para busca dos tipos de instituição de um bairro
     * @param $bairro
     * @param $id_municipio
     * @return AnonymousResourceCollection
     */
    public function getTiposInstituicoesBairro($bairro, $id_municipio): AnonymousResourceCollection
    {
        $bairro = urldecode($bairro);

        return TipoInstituicaoResource::collection(
            $this->municipioService->getTiposInstituicoesBairro($id_municipio, $bairro)
        );
    }
}
