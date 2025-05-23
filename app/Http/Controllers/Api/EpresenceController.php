<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEpresenceRequest;
use App\Http\Resources\EpresenceDetailResource;
use App\Http\Resources\EpresenceListResource;
use App\Http\Services\EpresenceService;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function store(StoreEpresenceRequest $request)
    {
        try {
            $data = $this->service->store($request->validated(), $request->user());
    
            return response()->json([
                'status'    => 'success',
                'message'   => 'Record created successfully',
                'data'      => new EpresenceDetailResource($data)
            ], 201); 

        } catch (AuthorizationException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'You are not authorized to perform this action'
            ], 403); 

        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'An unexpected error occurred while creating the record',
            ], 500); 
        }  
    }

    public function approve($id, Request $request)
    {
        try {
            $epresence = $this->service->approve($id);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Presence has been approved successfully',
                'data'      => new EpresenceDetailResource($epresence)
            ], 200); 

        } catch (AuthorizationException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'You are not authorized to approve this record'
            ], 403);

        } catch (Throwable $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'An unexpected error occurred while approving the presence',
            ], 500);
        }
    }

}
