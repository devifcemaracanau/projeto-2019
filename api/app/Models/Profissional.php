<?php
namespace App\Models;

/**
 * Class Profissional
 * @package App\Models
 * @property integer $id_profissional
 * @property string $no_profissional
 */
class Profissional extends BaseModel
{
    protected $table = 'td_profissional';
    protected $primaryKey = 'id_profissional';
    protected $keyType    = 'string';
    protected $guarded = ['id_profissional'];

    public $timestamps = false;

}
