<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Instituicao
 * @package App\Models
 * @property integer $id_unidade
 * @property string $no_fantasia
 * @property string $no_bairro
 * @property Collection $profissionais
 */
class Instituicao extends BaseModel
{
    protected $table      = 'td_instituicao';
    protected $primaryKey = 'id_unidade';
    protected $keyType    = 'string';
    protected $guarded    = ['id_unidade'];

    public $timestamps = false;

    /**
     * Relação de município
     * @return BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function tiposInstituicao()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function profissionais()
    {
        return $this->hasMany(Profissional::class, 'id_unidade', 'id_unidade');
    }
}
