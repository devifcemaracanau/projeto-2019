<?php

namespace App\Http\Resources;

use App\Models\Profissional;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfissionalResource extends JsonResource
{
    /** @var Profissional */
    public $resource;

    public function toArray($request)
    {
        $e = $this->resource;
        return [
            'id'   => $e->id_profissional,
            'nome' => $e->no_profissional,
        ];
    }
}
