<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $service;
    public function __construct(AuthService $service) {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->service->login($request->email, $request->password);

            return response()->json([
                'status'    => 'success',
                'message'   => 'Authentication successful',
                'data'      => [
                    'token'     => $result['token'],
                    'user'      => new UserResource($result['user']),
                ]
            ], 200);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Invalid credentials'
            ], 422);
        }
    }
}
