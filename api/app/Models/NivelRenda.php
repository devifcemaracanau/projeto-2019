<?php
namespace App\Models;

use Carbon\Carbon;
use Rennokki\QueryCache\Traits\QueryCacheable;

class NivelRenda extends BaseModel
{
    use QueryCacheable;
    public $cacheFor = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;

    protected $table      = 'td_nivel_renda';
    protected $primaryKey = 'id';
    protected $fillable   = ['descricao'];

    public $timestamps = false;
}
