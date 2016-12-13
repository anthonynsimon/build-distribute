<?php

namespace App;

use App\Ability;
use App\ProjectPermission;
use App\Project;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    
    protected $table = 'users';
    
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function projectPermissions()
    {
        return $this->hasMany('App\ProjectPermission');
    }
    
    public function projects()
    {
        $permissions = $this->projectPermissions;
        
        $projects = [];
        
        foreach ($permissions as $permission) {
            $projects[] = $permission->project;
        }
        return $projects;
    }
    
    public function projectNames()
    {
        $projects = $this->projects();
        
        $projectNames = array_map(function ($project) {
            return $project->name;
        }, $projects);
        
        return $projectNames;
    }
    
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function social()
    {
        return $this->hasMany('App\SocialiteUser');
    }
    
    public function hasRole($roleName)
    {
        if (!$roleName) {
            return false;
        }
        
        $input = explode('|', $roleName);

        foreach ($input as $key => $value) {
            if ($this->role->name === $value) {
                return true;
            }
        }
        
        return false;
    }
    
    // Laravel/Sqlite workaorund for no cascading
    public function deleteAndCascade()
    {
        $this->projectPermissions()->delete();
        $this->social()->delete();
        $this->delete();
    }
}
