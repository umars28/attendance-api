<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpresenceListResource;
use App\Http\Services\EpresenceService;
use Illuminate\Http\Request;
use Throwable;

class EpresenceController extends Controller
{
    protected $service;

    public function __construct(EpresenceService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $page  = (int) $request->get('page', 1);
    
            $groupedData  = $this->service->getList($request->user(), $limit, $page);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Success get data',
                'data'      => EpresenceListResource::collection($groupedData),
            ], 200);

        } catch (Throwable $e) {

            return response()->json([
                'status'    => 'error',
                'message'   => 'An unexpected server error occurred. Please try again later'   
            ], 500);        
        }
    }
}
