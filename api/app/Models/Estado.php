<?php
namespace App\Models;

use Carbon\Carbon;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * Class Estado
 * @package App\Models
 * @property integer $id_uf
 * @property string $no_uf_completo
 * @property string $uf_sigla
 */
class Estado extends BaseModel
{
    use QueryCacheable;
    public $cacheFor = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;

    protected $table      = 'tb_uf';
    protected $primaryKey = 'id_uf';
    protected $fillable   = ['uf_sigla', 'no_uf_completo'];

    public $timestamps = false;
}
