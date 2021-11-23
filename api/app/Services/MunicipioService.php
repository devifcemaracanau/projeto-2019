<?php
namespace App\Services;

use App\Models\Instituicao;
use App\Models\Municipio;
use App\Models\TipoInstituicao;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

/**
 * Regras de negócio para "Município"
 */
class MunicipioService {

    /**
     * Busca os municípios com base nos IDs informados
     * @param array $municipios_ids
     * @return Collection
     */
    public function getMunicipios(array $municipios_ids = []): Collection
    {
        return Municipio::query()->whereIn('id_municipio', $municipios_ids)
                                 ->orderby('no_municipio')
                                 ->get();
    }

    /**
     * Busca todas as instituições de um município
     * @param $municipio_id
     * @return Collection
     */
    public function getInstituicoes($municipio_id): Collection
    {
        return Instituicao::query()->where('id_municipio', $municipio_id)
                                   ->orderBy('no_fantasia')
                                   ->get();
    }

    /**
     * Busca todas os tipos de instituições de um município
     * @param $municipio_id
     * @return Collection
     */
    public function getTiposInstituicoes($municipio_id): Collection
    {
        return TipoInstituicao::query()
            ->whereIn('id_tipo_unidade', fn (Builder $query) =>
                $query->select('id_tipo_unidade')
                      ->distinct()
                      ->from(Instituicao::getTableName())
                      ->where('id_municipio', $municipio_id)
            )
            ->orderBy('ds_tipo_unidade')
            ->get();
    }

    /**
     * Busca todas os bairros de um município
     * @param $municipio_id
     * @return Collection|Instituicao[]
     */
    public function getBairros($municipio_id)
    {
        return Instituicao::query()->select('no_bairro')
                                   ->distinct()
                                   ->where('id_municipio', $municipio_id)
                                   ->orderBy('no_bairro')
                                   ->get();
    }

    /**
     * Busca todas os bairros de um município
     * @param $municipio_id
     * @param $bairro
     * @return Collection | Instituicao[]
     */
    public function getInstituicoesBairro($municipio_id, $bairro)
    {
        return Instituicao::query()->select()
                                   ->where('id_municipio', $municipio_id)
                                   ->where('no_bairro', $bairro)
                                   ->orderBy('no_bairro')
                                   ->get();
    }

    /**
     * Busca todas os tipos de instituições de um bairro
     * @param $municipio_id
     * @return mixed
     */
    public function getTiposInstituicoesBairro($municipio_id, $bairro)
    {
        return TipoInstituicao::query()
            ->whereIn('id_tipo_unidade', fn (Builder $query) =>
                $query->select('id_tipo_unidade')
                    ->distinct()
                    ->from(Instituicao::getTableName())
                    ->where('id_municipio', $municipio_id)
                    ->where('no_bairro', $bairro)
            )
            ->orderBy('ds_tipo_unidade')
            ->get();
    }
}
