<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Repositories\LeadRepository;
use App\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class LeadController extends Controller
{
    protected LeadService $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function story(LeadRequest $request)
    {
        try {

            $data = $request->all();
            $data['cpf'] = LeadRepository::formatCPF($request->cpf);

            $this->leadService->setLead($data);
            
            return response()->json([
                'success' => false,
                'message' => 'Cadastrado com sucesso!'
            ], Response::HTTP_CREATED);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
