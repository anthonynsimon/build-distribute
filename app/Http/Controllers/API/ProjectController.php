<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exceptions\CustomException;
use Validator;
use Illuminate\Http\Request;
use App\Project;
use App\Http\Requests;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::all();
            
            return response()->json($projects, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            
            $validator = Validator::make($input, [
                'name' => 'required|unique:projects',
                'ident' => 'required|unique:projects'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }
            
            $project = Project::create($request->all());
            
            return response()->json($project, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $project = Project::findByIdOrName($id);
            
            if (!$project) {
                throw new CustomException("Provided project id or name not found", 404);
            }
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
        
        return response()->json($project, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $project = Project::findByIdOrName($id);
            
            if ($project) {
                $project->update($request->all());
            }
            
            return response()->json($project, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findByIdOrName($id);
            
            if ($project) {
                $project->delete();
            }
            
            return response()->json($project, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }
}
