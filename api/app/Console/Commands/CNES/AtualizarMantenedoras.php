<?php

namespace App\Console\Commands\CNES;

use App\Models\Mantenedora;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AtualizarMantenedoras extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnes:atualizar:mantenedoras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Atualiza as Mantenedoras cadastrados";

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Throwable
     */
    public function handle()
    {
        // O nome do arquivo no qual as Instituições se encontram.
        // Pode ser baixado no endereço: http://cnes.datasus.gov.br/pages/downloads/arquivosBaseDados.jsp
        $fileName = 'tbMantenedora202011.csv';

        $mantenedorasHandler = Storage::readStream($fileName);
        $delimiter = ';';

        $h = array_flip(fgetcsv($mantenedorasHandler, 0, $delimiter));

        $mantenedoras = [];
        while (($i = fgetcsv($mantenedorasHandler, 0, $delimiter)) !== false) {
            $mantenedoras[] = [
                'nu_cnpj_mantenedora'         => $i[$h['NU_CNPJ_MANTENEDORA']],
                'no_razao_social_mantenedora' => $i[$h['NO_RAZAO_SOCIAL']],
                'no_logradouro_mantenedora'   => $i[$h['NO_LOGRADOURO']],
                'nu_endereco_mantenedora'     => $i[$h['NU_ENDERECO']],
                'no_complemento_mantenedora'  => $i[$h['NO_COMPLEMENTO']] ?: null,
                'no_bairro_mantenedora'       => $i[$h['NO_BAIRRO']],
                'cep_mantenedora'             => $i[$h['CO_CEP']],
                'id_municipio_mantenedora'    => $i[$h['CO_MUNICIPIO_MANT']] ?: ($i[$h['CO_MUNICIPIO']] ?: null),
                'id_regiao_saude_mantenedora' => $i[$h['CO_REGIAO_SAUDE']] ?: null,
                'nu_telefone_mantenedora'     => $i[$h['NU_TELEFONE']] ?: null,
                'st_fms_fes_mantenedora'      => $i[$h['ST_FMS_FES']] ?: null,
                'nu_cnpj_fms_fes_mantenedora' => $i[$h['NU_CNPJ_FMS_FES']] ?: null,
                'id_natjur_mantenedora'       => $i[$h['CO_NATUREZA_JUR']] ?: null,
                'id_gestor_mantenedora'       => $i[$h['CO_GESTOR']] ?: null
            ];
        }
        fclose($mantenedorasHandler);

        DB::transaction(function () use ($mantenedoras) {
            collect($mantenedoras)
                ->chunk(1000)
                ->each(function (Collection $mantenedorasChunk) {
                    Mantenedora::query()->upsert($mantenedorasChunk->toArray(), ['nu_cnpj_mantenedora']);
                });
        });

        $this->info('Carga concluída com sucesso!');
    }
}
