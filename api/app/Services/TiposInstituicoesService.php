<?php
namespace App\Services;

use App\Models\TipoInstituicao;
use Illuminate\Database\Eloquent\Collection;

/**
 * Regras de negócio para "Instituição"
 */
class TiposInstituicoesService {

    /**
     * Busca todas os tipos de instituições cadastradas no sistema
     * @return Collection|TipoInstituicao[]
     */
    public function getAll(): Collection
    {
        return TipoInstituicao::query()->select()
                                       ->orderBy('ds_tipo_unidade')
                                       ->get();
    }
}
