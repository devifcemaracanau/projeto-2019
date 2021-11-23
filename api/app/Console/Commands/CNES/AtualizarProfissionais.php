<?php

namespace App\Console\Commands\CNES;

use App\Models\Instituicao;
use App\Models\Profissional;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;

class AtualizarProfissionais extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnes:atualizar:profissionais';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os profissionais cadastrados';

    public function handle()
    {
        $hasFailure = false;
        $baseUrl = 'http://cnes.datasus.gov.br/services/estabelecimentos-profissionais/';

        foreach ($this->getInstituicoes() as $instituicao) {
            $response = Http::withHeaders([
                'Accept'  => 'application/json',
                'Referer' => 'http://cnes.datasus.gov.br/'
            ])->get($baseUrl . $instituicao->id_unidade);

            if (!$response->ok()) continue;

            $profissionaisCNES = $profissionaisCNES = collect($response->json());
            $profissionaisExistentes = $instituicao->profissionais
                                                   ->toBase()
                                                   ->map(function ($p) { return $p->id_cns; });

            DB::beginTransaction();
            try {
                echo "Atualizando profissionais para a instituição {$instituicao->id_unidade}...\n";
                $this->inserirNovosProfissionais($instituicao, $profissionaisCNES, $profissionaisExistentes);
                $this->dasabilitarProfissionaisDesvinculados($profissionaisCNES, $profissionaisExistentes);

                DB::commit();

                echo "Profissionais atualizados para a instituição {$instituicao->id_unidade}\n";
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao atualizar profissionais.', [
                    'instituicao' => $instituicao->id_unidade,
                    'erro'        => $e->getMessage()
                ]);

                echo "Erro ao atualizar profissionais para a instituição {$instituicao->id_unidade}\n";
            }
        }

        echo $hasFailure
            ? "Atualização parcialmente realizada. Verifique o log de erros.\n"
            : "Atualização realizada com sucesso!\n";
    }

    protected function inserirNovosProfissionais(
        Instituicao $instituicao,
        Collection $profissionaisCNES,
        Collection $profissionaisExistentes
    ): void
    {
        $tableColumns = ['no_profissional', 'id_cns', 'id_cbo', 'id_unidade', 'id_vinculo'];
        $profissionaisExistentes = $profissionaisExistentes->toArray();
        $novosProfissionaisSQL = $profissionaisCNES->filter(function ($profissionalCNES) use ($profissionaisExistentes) {
                return !in_array($profissionalCNES['cns'], $profissionaisExistentes);
            })
            ->map(function ($profissionalCNES) use ($instituicao) {
                $vinculoQbo = "
                    SELECT
                        sbv.id_vinculo
                    FROM
                        td_subvinculo sbv
                        inner join td_tipo_vinculo tpv on tpv.id_tipo_vinculo = sbv.id_tipo_vinculo and tpv.id_vinculacao = sbv.id_vinculacao
                        inner join td_vinculacao vnc on vnc.id_vinculacao = tpv.id_vinculacao
                    WHERE
                        sbv.ds_subvinculo = '{$profissionalCNES['subVinculo']}'
                        AND tpv.ds_tipo_vinculo = '{$profissionalCNES['vinculo']}'
                        AND vnc.ds_vinculacao = '{$profissionalCNES['vinculacao']}'
                ";

                return "
                    SELECT
                        '{$profissionalCNES['nome']}' as no_profissional,
                        '{$profissionalCNES['cns']}'  as id_cns,
                        '{$profissionalCNES['cbo']}'  as id_cbo,
                        '{$instituicao->id_unidade}'  as id_unidade,
                        ({$vinculoQbo})               as id_vinculo
                ";
            })
            ->join("
                UNION ALL
            ");

        if (!empty($novosProfissionaisSQL))
            DB::table(Profissional::getTableName())->insertUsing($tableColumns, $novosProfissionaisSQL);
    }

    protected function dasabilitarProfissionaisDesvinculados(
        Collection $profissionaisCNES,
        Collection $profissionaisExistentes
    ) :void
    {
        $profissionaisDesvinculados = $profissionaisExistentes->diff(
            $profissionaisCNES->map(function ($p) { return $p['cns']; })
        );

        if ($profissionaisDesvinculados->isNotEmpty()) {
            DB::table(Profissional::getTableName())
                ->whereIn('id_cns', $profissionaisDesvinculados)
                ->update([
                    'flg_ativo' => false
                ]);
        }
    }

    /**
     * @return LazyCollection|Instituicao[]
     */
    private function getInstituicoes(): LazyCollection
    {
        return Instituicao::query()
            ->select('id_unidade')
            ->with(['profissionais'])
            ->cursor();
    }
}
