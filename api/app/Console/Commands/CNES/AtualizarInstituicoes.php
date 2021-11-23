<?php

namespace App\Console\Commands\CNES;

use App\Models\Instituicao;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class AtualizarInstituicoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnes:atualizar:instituicoes
                            {--municipio= : O código IBGE do município o qual as instituições serão atualizadas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Atualiza as Instituições cadastrados";

    public function handle()
    {
        $municipioOption = (int) $this->option('municipio');

        if (!$municipioOption)
            throw new \InvalidArgumentException('Informe o município para atualização das instituições!');

        $municipios = $this->loadMunicipios();

        // O nome do arquivo no qual as Instituições se encontram.
        // Pode ser baixado no endereço: http://cnes.datasus.gov.br/pages/downloads/arquivosBaseDados.jsp
        $fileName = $this->getLastFileByPattern('/^tbEstabelecimento\d{6}\.csv$/');

        $instituicoesHandler = Storage::readStream($fileName);
        $delimiter = ';';

        $h = array_flip(fgetcsv($instituicoesHandler, 0, $delimiter));

        $instituicoes = [];
        while (($i = fgetcsv($instituicoesHandler, 0, $delimiter)) !== false) {
            $idMun = $i[$h['CO_MUNICIPIO_GESTOR']];

            // Atualiza apenas as instituições do município informado
            if ((int) $idMun !== $municipioOption) continue;

            $instituicoes[] = [
                'id_unidade'            => $i[$h['CO_UNIDADE']],
                'nu_cnpj_cpf'           => $i[$h['NU_CNPJ']] ?: ($i[$h['NU_CPF']] ?: null),
                'no_razao_social'       => $i[$h['NO_RAZAO_SOCIAL']],
                'no_fantasia'           => $i[$h['NO_FANTASIA']] ?: null,
                'endereco'              => "{$i[$h['NO_LOGRADOURO']]}, {$i[$h['NU_ENDERECO']]}, {$i[$h['NO_BAIRRO']]}, {$municipios[$idMun]['NO_MUNICIPIO']}-{$municipios[$idMun]['CO_SIGLA_ESTADO']}",
                'no_bairro'             => $i[$h['NO_BAIRRO']] ?: null,
                'cep'                   => $i[$h['CO_CEP']],
                'nu_cnpj_mantenedora'   => $i[$h['NU_CNPJ_MANTENEDORA']] ?: null,
                'id_cnes'               => $i[$h['CO_CNES']],
                'id_tipo_unidade'       => (int) $i[$h['TP_UNIDADE']],
                'nu_latitude'           => $i[$h['NU_LATITUDE']] ?: null,
                'nu_longitude'          => $i[$h['NU_LONGITUDE']] ?: null,
                'id_municipio'          => $idMun,
                'id_nivel_complexidade' => null, // Classificação manual
                'id_nivel_atencao'      => null, // Classificação manual
            ];
        }
        fclose($instituicoesHandler);

        Instituicao::query()->upsert($instituicoes, ['id_unidade']);

        $this->info('Carga concluída com sucesso!');
    }

    protected function loadMunicipios(): array
    {
        // O nome do arquivo no qual as Instituições se encontram.
        // Pode ser baixado no endereço: http://cnes.datasus.gov.br/pages/downloads/arquivosBaseDados.jsp
        $fileName = $this->getLastFileByPattern('/^tbMunicipio\d{6}\.csv$/');

        $munHandler = Storage::readStream($fileName);
        $delimiter = ';';

        $h = array_flip(fgetcsv($munHandler, 0, $delimiter));

        $municipios = [];
        while (($m = fgetcsv($munHandler, 0, $delimiter)) !== false) {
            $municipios[$m[$h['CO_MUNICIPIO']]] = [
                'NO_MUNICIPIO'    => $m[$h['NO_MUNICIPIO']],
                'CO_SIGLA_ESTADO' => $m[$h['CO_SIGLA_ESTADO']]
            ];
        }
        fclose($munHandler);

        return $municipios;
    }

    protected function getLastFileByPattern(string $pattern): string
    {
        return Arr::first(preg_grep($pattern, scandir(storage_path('app'), SCANDIR_SORT_DESCENDING)));
    }
}
