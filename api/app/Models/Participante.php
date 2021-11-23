<?php
namespace App\Models;

class Participante extends BaseModel
{
    protected $table = 'td_participante';
    protected $primaryKey = 'id_participante';
    protected $guarded = ['id_participante'];

    public $timestamps = false;

    public function sexo() {
        return $this->belongsTo(Sexo::class, 'id_sexo');
    }

    /**
     * @param string $anoMes Ano e o mês do protocolo. Ex.: 201801 (Janeiro de 2018)
     * @return int|null
     */
    public function getLastProtocolo(string $anoMes) {
        $protocolo = $this->select('participante_protocolo')
                    ->where('participante_protocolo', 'LIKE', "$anoMes%")
                    ->first();

        if (!$protocolo) return null;

        return (int) substr($protocolo->participante_protocolo, 6);
    }

}
