<?php

namespace App\Providers;

use App\Helpers\BuildTokenService;
use Gate;
use Request;
use Auth;
use App\User;
use App\Project;
use App\ProjectPermission;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public static function getPathNamedResources()
    {
        $pathElements = array_filter(explode('/', Request::path()));
        $resources = array_map(function ($x) {
            return urldecode($x);
        }, $pathElements );
        return $resources;
    }
    
    public static function getAllowedProjects()
    {
        $user = Auth::user();
        
        $allowedProjects = [];
        
        if (Gate::allows('viewAllProjects')) {
            $allowedProjects = Project::all();
        } else {
            $allowedProjects = $user->projects();
        }
        
        return $allowedProjects;
    }
    
    public static function generateUrlSafeToken($buildId)
    {
        return BuildTokenService::generateBuildToken($buildId);
    }

    public function register()
    {
    }
}
