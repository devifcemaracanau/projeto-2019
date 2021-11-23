<?php
namespace App\Models;

use Carbon\Carbon;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Escolaridade extends BaseModel
{
    use QueryCacheable;
    public $cacheFor = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;

    protected $table      = 'td_tipo_escolaridade';
    protected $primaryKey = 'id_tipo_escolaridade';
    protected $fillable   = ['ds_tipo_escolaridade'];

    public $timestamps = false;
}
