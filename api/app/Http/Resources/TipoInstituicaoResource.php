<?php

namespace App\Http\Resources;

use App\Models\TipoInstituicao;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoInstituicaoResource extends JsonResource
{
    /** @var TipoInstituicao */
    public $resource;

    public function toArray($request)
    {
        $ti = $this->resource;
        return [
            'id'   => $ti->id_tipo_unidade,
            'nome' => $ti->ds_tipo_unidade,
        ];
    }
}
