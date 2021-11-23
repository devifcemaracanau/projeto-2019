<?php

namespace App\Http\Resources;

use App\Models\Municipio;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipioResource extends JsonResource
{
    /** @var Municipio */
    public $resource;

    public function toArray($request)
    {
        $m = $this->resource;
        return [
            'id'   => $m->id_municipio,
            'nome' => $m->no_mun_completo,
        ];
    }
}
