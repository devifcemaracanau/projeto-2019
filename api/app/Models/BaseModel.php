<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * Retorna o nome da tabela do model
     * @return string
     */
    public static function getTableName()
    {
        return (new static)->getTable();
    }
}