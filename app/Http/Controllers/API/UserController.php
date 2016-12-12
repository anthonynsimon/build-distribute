<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            
            return response()->json($users, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof Exception ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }
    
    public function show($id)
    {
        try {
            $user = User::find($id);
            
            return response()->json($user, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof Exception ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }
}
