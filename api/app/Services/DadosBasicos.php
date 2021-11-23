<?php
namespace App\Services;

use App\Models\Escolaridade;
use App\Models\NivelRenda;
use App\Models\Sexo;
use Illuminate\Database\Eloquent\Collection;

/**
 * Regras de negócio para "Dados Básicos"
 */
class DadosBasicos {

    /**
     * Busca todos as escolaridades cadastradas no sistema
     * @return array
     */
    public function getAll() {
        return [
            'escolaridades' => $this->getEscolaridades(),
            'niveis_renda'  => $this->getNiveisRenda(),
            'sexos'         => $this->getSexos(),
        ];

    }

    /**
     * @return Collection|Escolaridade[]
     */
    public function getEscolaridades(): Collection
    {
        return Escolaridade::query()->orderBy('id_tipo_escolaridade')->get();
    }

    /**
     * @return Collection|NivelRenda[]
     */
    public function getNiveisRenda(): Collection
    {
        return NivelRenda::query()->orderBy('id')->get();
    }

    /**
     * @return Collection|Sexo[]
     */
    public function getSexos(): Collection
    {
        return Sexo::query()->orderBy('id_sexo')->get();
    }
}
