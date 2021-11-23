<?php

namespace App\Http\Controllers\Api;

use App\Services\RespostaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RespostasController extends Controller
{
    protected RespostaService $respostaService;

    /**
     * RespostasController constructor.
     * @param RespostaService $respostasBusiness
     */
    public function __construct(RespostaService $respostasBusiness)
    {
        $this->respostaService = $respostasBusiness;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        $data = $request->all();
        try {
            $numProtocolo = $this->respostaService->save($data);
            $response = ['error' => false, 'protocolo' => $numProtocolo];
        } catch (\Exception $e) {
            $response = ['error' => true, 'message' => 'Não foi possível salvar as respostas. Por favor, tente novamente.'];
        }

        return response()->json($response);
    }
}
