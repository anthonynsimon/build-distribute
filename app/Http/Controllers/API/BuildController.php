<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exceptions\CustomException;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Build;
use App\Project;

class BuildController extends Controller
{
    public function index(Request $request)
    {
        // Optional route query string
        $platform = $request->input('platform');
        $orderBy = $request->input('orderBy');
        $orderType = $request->input('orderType');
        
        $builds = Build::select('*');
            
        if ($platform) {
            $builds = $builds->where('platform', $platform);
        }
        if ($orderBy) {
            $builds = $builds->orderBy($orderBy, $orderType ? $orderType : 'desc');
        }
        
        $builds = $builds->get();
        
        return response()->json($builds, 200);
    }

    public function store(Request $request)
    {
        try {
            $input = $request->all();
            
            $validator = Validator::make($input, [
                'projectIdent' => 'required',
                'bundleIdentifier' => 'required',
                'installFolder' => 'required',
                'installFileName' => 'required',
                'version' => 'required',
                'buildNumber' => 'required',
                'platform' => 'required',
                'revision' => 'required'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }

            $platformMappings = array(
                'iphone' => 'ios',
                'ipad' => 'ios',
                'ios' => 'ios',
                'android' => 'android'
            );
        
            $input->platform = $platformMappings[strtolower($input->platform)];
            
            $project = Project::where('ident', '=', $input['projectIdent'])->first();
            
            if (!$project) {
                throw new CustomException("Provided project id or name not found, "
                                            ."you must specify an existing project "
                                            ."to associate with this built", 404);
            }

            $build = $project->builds()->create($input);

            return response()->json($build, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            return response()->json(['errors' => ['message' => $e->getMessage()]], $statusCode);
        }
    }

    public function show($id)
    {
        try {
            $build = Build::find($id);
            
            if (!$build) {
                throw new CustomException("Provided build id not found", 404);
            }
            
            return response()->json($build, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            return response()->json(['errors' => ['message' => $e->getMessage()]], $statusCode);
        }
    }
    
    public function getProject($id)
    {
        try {
            $build = Build::find($id);
            
            if (!$build) {
                throw new CustomException("Provided build id not found", 404);
            }
            
            return response()->json($build->project, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            return response()->json(['errors' => ['message' => $e->getMessage()]], $statusCode);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $build = Build::find($id);
            
            if (!$build) {
                throw new CustomException("Provided build id not found", 404);
            }
            
            $build->update($request->all());
            
            return response()->json($build, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            return response()->json(['errors' => ['message' => $e->getMessage()]], $statusCode);
        }
    }
    
    public function destroy($id)
    {
        try {
            $build = Build::find($id);
            
            if (!$build) {
                throw new CustomException("Provided build id not found", 404);
            }
            
            $build->delete();
            
            return response()->json($build, 200);
        } catch (\Exception $e) {
            $statusCode = $e instanceof CustomException ? $e->getCode() : 500;
            return response()->json(['errors' => ['message' => $e->getMessage()]], $statusCode);
        }
    }
}
