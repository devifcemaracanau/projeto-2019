<?php
namespace App\Services;

use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Collection;

/**
 * Regras de negócio para "Estado"
 */
class EstadoService {

    /**
     * Busca todos os estados cadastrados no sistema
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Estado::query()->whereNotIn('uf_sigla', ['IG', 'FN'])
                              ->orderBy('no_uf_completo')
                              ->get();
    }

    /**
     * Busca os municípios de um determinado estado
     * @return Collection
     */
    public function getMunicipios(int $estado_id): Collection
    {
        return Municipio::query()->where('id_uf', $estado_id)
                                ->orderBy('no_mun_completo')
                                ->get();
    }
}
