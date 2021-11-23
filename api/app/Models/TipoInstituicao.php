<?php
namespace App\Models;

use Carbon\Carbon;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * Class TipoInstituicao
 * @package App\Models
 * @property integer $id_tipo_unidade
 * @property string $ds_tipo_unidade
 */
class TipoInstituicao extends BaseModel
{
    use QueryCacheable;
    public $cacheFor = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;

    protected $table      = 'td_tipo_unidade';
    protected $primaryKey = 'id_tipo_unidade';
    protected $fillable   = ['id_tipo_unidade'];

    public $timestamps = false;
}
