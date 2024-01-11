<?php

namespace App\Http\Controllers;

use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    protected ApplicationService $applicationService;


    public function __construct(ApplicationService $applicationService)
    {
        return $this->applicationService = $applicationService;

    }
    public function getAll(Request $request)
    {
        $filters = $request->all();
        $query = $this->applicationService->getFilterApplicationAndStatus($filters);

        if (count($filters) > 0) {
            $applications = $query->get();
        }else {
            $applications = $this->applicationService->getApplicationAll();
        }

        return response()->json(['applications' => $applications]);
    }

    public function create(Request $request)
    {
        $result = $this->applicationService->createApplication($request->all());

        return response()->json(['data' => $result]);
    }

    public function respondToApplication($id, Request $request)
    {
        $result = $this->applicationService->respondToApplication($id, $request->all());

        return response()->json($result);
    }

}
