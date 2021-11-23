<?php

namespace App\Console\Commands\CNES;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AtualizarNaturezasJuridicas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnes:atualizar:naturezas-juridicas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza a Natureza Jurídica das Instituições cadastradas';

    public function handle()
    {
        // O nome do arquivo no qual as Instituições se encontram.
        // Pode ser baixado no endereço: http://cnes.datasus.gov.br/pages/downloads/arquivosBaseDados.jsp
        $fileName = 'tbNaturezaJuridica202011.csv';

        $natJurHandler = Storage::readStream($fileName);
        $delimiter = ';';

        $h = array_flip(fgetcsv($natJurHandler, 0, $delimiter));

        $naturezasJuridicas = [];
        while (($i = fgetcsv($natJurHandler, 0, $delimiter)) !== false) {
            $naturezasJuridicas[] = [
                'id_natjur' => $i[$h['CO_NATUREZA_JUR']],
                'ds_natjur' => $i[$h['DS_NATUREZA_JUR']],
            ];
        }
        fclose($natJurHandler);

        DB::table('dfdwp.td_natjur')->upsert($naturezasJuridicas, ['id_natjur']);

        $this->info('Carga concluída com sucesso!');
    }
}
