<?php

namespace App\Http\Resources;

use App\Models\Instituicao;
use Illuminate\Http\Resources\Json\JsonResource;

class BairroResource extends JsonResource
{
    /** @var Instituicao */
    public $resource;

    public function toArray($request)
    {
        $b = $this->resource;
        return [
            'bairro' => $b->no_bairro
        ];
    }
}
