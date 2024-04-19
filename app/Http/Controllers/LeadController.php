<?php

namespace App\Http\Controllers;

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

    public function story(Request $request)
    {
        try {
            $this->leadService->setLead($request->all());
            return response()->json([
                'success' => false,
                'message' => 'Cadastrado com sucesso!'
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? Response::HTTP_BAD_REQUEST);
        }
    }
}
