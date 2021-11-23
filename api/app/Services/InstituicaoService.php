<?php
namespace App\Services;

use App\Models\Instituicao;
use App\Models\Municipio;
use App\Models\Profissional;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

/**
 * Regras de negócio para "Instituição"
 */
class InstituicaoService {

    /**
     * Busca todas as instituições cadastradas no sistema
     * @param array $filters
     * @return Collection|Instituicao[]
     */
    public function getAll(array $filters)
    {
        $instituicaoTable = Instituicao::getTableName();

        $filterMap = [
            'bairro'           => 'no_bairro',
            'municipio'        => 'id_municipio',
            'tipo_instituicao' => 'id_tipo_unidade'
        ];

        $query = Instituicao::query()->select()->orderBy('no_fantasia');

        foreach ($filters as $filter => $value) {
            if ($filter === 'uf') {
                $query->join(Municipio::getTableName(). ' as municipio', fn (JoinClause $join) =>
                    $join->on('municipio.id_municipio', "$instituicaoTable.id_municipio")
                         ->where('municipio.id_uf', $value)
                );
            } else {
                $query->where("$instituicaoTable.{$filterMap[$filter]}", $value);
            }
        }

        return $query->get();
    }

    /**
     * @param string $id_unidade
     * @return Collection
     */
    public function getProfissionais(string $id_unidade): Collection
    {
        return Profissional::query()->where('id_unidade', $id_unidade)
                                    ->get();
    }
}
