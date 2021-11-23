<?php

namespace App\Http\Resources;

use App\Models\Estado;
use Illuminate\Http\Resources\Json\JsonResource;

class EstadoResource extends JsonResource
{
    /** @var Estado */
    public $resource;

    public function toArray($request)
    {
        $e = $this->resource;
        return [
            'id'   => $e->id_uf,
            'nome' => $e->no_uf_completo,
        ];
    }
}
