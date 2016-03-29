<?php

namespace App\Providers;

use Auth;
use App\ProjectPermission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
		
		$gate->before(function($user, $ability) {
			if ($user->hasRole('superAdmin')) {
				return true;
			}
		});
		
		$gate->define('viewAllProjects', function($user) {
			$allowedRoles = ['wlpTeam'];
			return $user->hasRole(implode("|", $allowedRoles));
		});
				
		$gate->define('viewProject', function($user, $projectId) {
			$bypassRoles = ['wlpTeam'];
			
			if ($user->hasRole(implode("|", $bypassRoles))) {
				return true;
			}
			
			return ProjectPermission::checkForPermission($user->id, $projectId);
		});
    }
}
