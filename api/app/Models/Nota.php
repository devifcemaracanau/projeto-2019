<?php
namespace App\Models;

class Nota extends BaseModel
{
    protected $table = 'tf_notas';
    protected $primaryKey = 'id_nota';
    protected $guarded = ['id_nota'];

    public $timestamps = false;
}
