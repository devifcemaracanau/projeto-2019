<?php

namespace App\Console\Commands\CNES;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AtualizarCBO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnes:atualizar:cbo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Atualiza os CBO's cadastrados";

    public function handle()
    {
        // O nome do arquivo no qual os CBO's se encontram.
        // Pode ser baixado no endereço: http://cnes.datasus.gov.br/pages/downloads/arquivosBaseDados.jsp
        $fileName = 'tbAtividadeProfissional202002.csv';

        $cboHandle = Storage::readStream($fileName);
        $delimiter = ';';

        $baseBuilder = DB::table('dfdwp.td_cbo_atividade_profissional')->select('id_cbo');
        while (($cbo = fgetcsv($cboHandle, 0, $delimiter)) !== false) {
            $idCbo = $cbo[0];
            if (!is_numeric($idCbo)) continue;

            $qb = clone $baseBuilder;
            if ($qb->where('id_cbo', $idCbo)->exists())
                continue;

            $qb->insert([
                'id_cbo'               => $idCbo,
                'ds_cbo'               => $cbo[1],
                'tp_cbo_saude'         => $cbo[3],
                'st_cbo_regulamentado' => $cbo[4]
            ]);
        }
        fclose($cboHandle);

        $this->info('Carga concluída com sucesso!');
    }
}
