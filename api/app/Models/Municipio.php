<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Municipio
 * @package App\Models
 * @property integer $id_municipio
 * @property string $no_mun_completo
 */
class Municipio extends BaseModel
{
    protected $table      = 'tb_municipio';
    protected $primaryKey = 'id_municipio';
    protected $keyType    = 'string';
    protected $guarded   = ['id_municipio'];

    public $timestamps = false;

    /**
     * Relação de instituições
     * @return HasMany
     */
    public function instituicoes(): HasMany
    {
        return $this->hasMany(Instituicao::class, 'id_municipio', 'id_municipio');
    }

}
