<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avaliacao extends BaseModel
{
    protected $table = 'td_avaliacoes';
    protected $primaryKey = 'id_avaliacao';
    protected $guarded = ['id_avaliacao'];

    public $timestamps = false;

    /**
     * Relação notas-profissionais
     * @return HasMany
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class, 'id_avaliacao', 'id_avaliacao');
    }

    /**
     * Relação notas-profissionais
     * @return BelongsToMany
     */
    public function profissionais(): BelongsToMany
    {
        return $this->belongsToMany(Profissional::class, 'rl_profissional_avaliacao', 'id_avaliacao', 'id_profissional');
    }
}
