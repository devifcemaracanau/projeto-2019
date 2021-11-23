<?php
namespace App\Services;

use App\Models\Avaliacao;
use App\Models\Nota;
use App\Models\Participante;
use Illuminate\Support\Facades\DB;

/**
 * Regras de negócio para "Respostas"
 */
class RespostaService {

    const ETAPA_20Q = 5;
    const ETAPA_12Q = 6;

    const TIPO_ATENDIMENTO_MEDICO              = 1;
    const TIPO_ATENDIMENTO_ENFERMAGEM          = 2;
    const TIPO_ATENDIMENTO_DENTISTA            = 3;
    const TIPO_ATENDIMENTO_EXAMES_LAB          = 4;
    const TIPO_ATENDIMENTO_FISIOTERAPIA        = 5;
    const TIPO_ATENDIMENTO_FARMACIA            = 6;
    const TIPO_ATENDIMENTO_INTERNACAO          = 7;
    const TIPO_ATENDIMENTO_PSICOLOGIA          = 8;
    const TIPO_ATENDIMENTO_TERAPIA_OCUPACIONAL = 9;
    const TIPO_ATENDIMENTO_ASSISTENCIA_SOCIAL  = 10;
    const TIPO_ATENDIMENTO_NUTRICAO            = 11;
    const TIPO_ATENDIMENTO_FONOAUDIOLOGIA      = 12;

    /**
     * Salva as respostas no banco
     * @param array $data
     * @return string O Número de protocolo gerado
     * @throws \Exception
     */
    public function save(array $data) {
        $isAnonimo = isset($data['isAnonimo']) && $data['isAnonimo'];
        $nome      = $isAnonimo ? null : $data['nome'];
        $email     = $isAnonimo ? null : $data['email'];
        $documento = preg_replace('/[^\d]/', '', $data['cpf-rg']);

        $participanteId = Participante::query()->firstOrCreate([], [
            'no_participante'      => $nome,
            'nu_documento'         => $documento,
            'id_sexo'              => $data['sexo'],
            'id_tipo_escolaridade' => $data['grau-escolaridade'],
            'id_plano_saude'       => $data['possui-plano'],
            'id_nivel_renda'       => $data['nivel-renda'],
            'ano_nascimento'       => $data['ano-nascimento'],
            'email'                => $email,
            'id_tipo_participante' => 'U'
        ])->id_participante;

        $questoesNotasMap = [];
        foreach ($data as $key => $value) {
            if (!preg_match('/qt(\d{2})([ab])(Sat|Exp)/', $key, $matches)) continue;
            $num   = (int) $matches[1];
            $etapa = $matches[2] == 'a' ? 6 : 5;
            $tipo  = $matches[3] === 'Sat' ? 'satisfacao' : 'expectativa';

            $variante = 'A';
            switch ($etapa) {
                case self::ETAPA_20Q:
                    switch ($num) {
                        case 9:
                        case 10:
                            switch ($data['tipo-atendimento']) {
                                case self::TIPO_ATENDIMENTO_MEDICO:
                                    $variante = 'A';
                                    break;
                                case self::TIPO_ATENDIMENTO_DENTISTA;
                                    $variante = 'B';
                                    break;
                                case self::TIPO_ATENDIMENTO_ENFERMAGEM:
                                    $variante = 'C';
                                    break;
                                case self::TIPO_ATENDIMENTO_FISIOTERAPIA;
                                    $variante = 'D';
                                    break;
                                case self::TIPO_ATENDIMENTO_INTERNACAO:
                                    $variante = 'E';
                                    break;
                                case self::TIPO_ATENDIMENTO_EXAMES_LAB:
                                    $variante = 'F';
                                    break;
                            }
                            break;
                    }
                    break;

                case self::ETAPA_12Q:
                    switch ($num) {
                        case 6:
                        case 7:
                            switch ($data['tipo-atendimento']) {
                                case self::TIPO_ATENDIMENTO_MEDICO:
                                    $variante = 'A';
                                    break;
                                case self::TIPO_ATENDIMENTO_DENTISTA;
                                    $variante = 'B';
                                    break;
                                case self::TIPO_ATENDIMENTO_ENFERMAGEM:
                                    $variante = 'C';
                                    break;
                                case self::TIPO_ATENDIMENTO_FISIOTERAPIA;
                                    $variante = 'D';
                                    break;
                                case self::TIPO_ATENDIMENTO_INTERNACAO:
                                    $variante = 'E';
                                    break;
                                case self::TIPO_ATENDIMENTO_EXAMES_LAB:
                                    $variante = 'F';
                                    break;
                                case self::TIPO_ATENDIMENTO_PSICOLOGIA:
                                    $variante = 'G';
                                    break;
                                case self::TIPO_ATENDIMENTO_TERAPIA_OCUPACIONAL:
                                    $variante = 'H';
                                    break;
                                case self::TIPO_ATENDIMENTO_ASSISTENCIA_SOCIAL:
                                    $variante = 'I';
                                    break;
                                case self::TIPO_ATENDIMENTO_NUTRICAO:
                                    $variante = 'J';
                                    break;
                                case self::TIPO_ATENDIMENTO_FONOAUDIOLOGIA:
                                    $variante = 'K';
                                    break;
                            }
                            break;
                        case 8:
                            switch ($data['tipo-atendimento']) {
                                case self::TIPO_ATENDIMENTO_FARMACIA:
                                    $variante = 'A';
                                    break;
                                case self::TIPO_ATENDIMENTO_EXAMES_LAB:
                                    $variante = 'B';
                                    break;
                                case self::TIPO_ATENDIMENTO_INTERNACAO:
                                    $variante = 'C';
                                    break;
                                case self::TIPO_ATENDIMENTO_NUTRICAO:
                                    $variante = 'D';
                                    break;
                            }
                            break;
                        case 9:
                            //@TODO: Implementar após verificação se a questão 09 terá variante
                            break;
                    }
                    break;
            }

            $questaoId = sprintf('%d%s%d', $num, $variante, $etapa);
            if (!isset($questoesNotasMap[$questaoId])) {
                $questoesNotasMap[$questaoId] = ['etapa' => $etapa];
            }
            $questoesNotasMap[$questaoId][$tipo] = $value;
        }

        $notasModels = [];
        foreach ($questoesNotasMap as $questaoId => $nota) {
            $notasModels[] = new Nota([
                'id_etapa'                         => $nota['etapa'],
                'id_questao_constructo'            => $questaoId,
                'nu_nota_profissional_expectativa' => $nota['expectativa'] ?? null,
                'nu_nota_usuario_percebida'        => $nota['satisfacao'],
            ]);
        }

        /** @var Avaliacao $avaliacao */
        $avaliacao = DB::transaction(function () use ($participanteId, $notasModels) {
            $avaliacaoModel = new Avaliacao([
                'id_fase'         => $data['fase'] ?? 10,
                'id_unidade'      => $data['instituicao'],
                'id_participante' => $participanteId,
                'dt_avaliacao'    => new \DateTime(),
                'feedback'        => $data['feedback']
            ]);
            $avaliacaoModel->save();
            $avaliacaoModel->profissionais()->attach($data['profissionais']);
            $avaliacaoModel->notas()->saveMany($notasModels);

            return $avaliacaoModel->fresh();
        });

        return $avaliacao->nu_protocolo;
    }
}
