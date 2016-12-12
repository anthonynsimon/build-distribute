<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Build;
use App\Project;

class ProjectBuildController extends Controller
{
    public function index($projectId)
    {
        try {
            $project = Project::findByIdOrName($projectId);
            
            if ($project) {
                $builds = $project->builds;
                return response()->json($builds, 200);
            } else {
                throw new \Exception("Provided project id or name not found", 404);
            }
        } catch (\Exception $e) {
            $statusCode = $e instanceof Exception ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }
    
    public function show($projectId, $buildId)
    {
        try {
            $build = Build::find($buildId);
            if ($build) {
                return response()->json($build, 200);
            } else {
                throw new \Exception("Provided build id not found", 404);
            }
        } catch (\Exception $e) {
            $statusCode = $e instanceof Exception ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }
    
    // TODO change default query to return head for each platform, no need to specify
    public function indexHead(Request $request, $projectId)
    {
        try {
            // Query String
            $platform = $request->input('platform');
            $project = Project::findByIdOrName($projectId);
            $builds = $project->builds();

            if (!$platform) {
                throw new \Exception("Please provide a head platform as a query string", 400);
            }
            
            $builds = $builds->where('platform', $platform);
            $builds = $builds->orderBy('revision', 'desc');
            $builds = $builds->first();
            
            return response()->json($builds, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof Exception ? $e->getCode() : 500;
            
            return response()->json([
                'errors' => ['message' => $e->getMessage()]
            ], $statusCode);
        }
    }
}
