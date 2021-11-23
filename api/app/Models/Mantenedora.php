<?php
namespace App\Models;

class Mantenedora extends BaseModel
{
    protected $table      = 'td_mantenedora';
    protected $primaryKey = 'nu_cnpj_mantenedora';

    public $timestamps = false;
}
