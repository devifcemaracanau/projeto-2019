<?php
namespace App\Models;

use Carbon\Carbon;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Sexo extends BaseModel
{
    use QueryCacheable;
    public $cacheFor = Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR * Carbon::SECONDS_PER_MINUTE;

    protected $table      = 'td_sexo';
    protected $primaryKey = 'id_sexo';
    protected $fillable   = ['ds_sexo'];

    public $timestamps = false;
}
