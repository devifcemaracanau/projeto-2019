<?php

namespace App\Http\Resources;

use App\Models\Instituicao;
use Illuminate\Http\Resources\Json\JsonResource;

class InstituicaoResource extends JsonResource
{
    /** @var Instituicao */
    public $resource;

    public function toArray($request)
    {
        $e = $this->resource;
        return [
            'id'   => $e->id_unidade,
            'nome' => $e->no_fantasia,
        ];
    }
}
