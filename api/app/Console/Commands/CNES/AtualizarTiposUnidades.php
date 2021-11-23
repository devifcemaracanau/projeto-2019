<?php

namespace App\Console\Commands\CNES;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AtualizarTiposUnidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnes:atualizar:tipos-unidades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os Tipos de Unidades cadastrados';

    public function handle()
    {
        // O nome do arquivo no qual as Instituições se encontram.
        // Pode ser baixado no endereço: http://cnes.datasus.gov.br/pages/downloads/arquivosBaseDados.jsp
        $fileName = 'tbTipoUnidade202011.csv';

        $tpUnHandler = Storage::readStream($fileName);
        $delimiter = ';';

        $h = array_flip(fgetcsv($tpUnHandler, 0, $delimiter));

        $tiposUnidades = [];
        while (($i = fgetcsv($tpUnHandler, 0, $delimiter)) !== false) {
            $tiposUnidades[] = [
                'id_tipo_unidade' => $i[$h['CO_TIPO_UNIDADE']],
                'ds_tipo_unidade' => $i[$h['DS_TIPO_UNIDADE']],
            ];
        }
        fclose($tpUnHandler);

        DB::table('dfdwp.td_tipo_unidade')->upsert($tiposUnidades, ['id_tipo_unidade']);

        $this->info('Carga concluída com sucesso!');
    }
}
