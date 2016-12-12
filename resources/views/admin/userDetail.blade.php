@extends('layouts.app') @section('mainView')
@if (isset($user))
@include('admin.editRoleModal') @yield('editRoleModal')
@include('admin.deleteUserModal') @yield('deleteUserModal')
<div class="card soft-shadow">
	<div class="card-header text-white bg-primary">
		<div class="row">
			<div class="col-md-12">
				<label><h5>User Management</h5></label>
				@if(!$user->hasRole('superAdmin'))
				<div class="btn btn-danger btn-sm pull-xs-right" data-toggle="modal" data-target="#deleteUserModal">Delete Permanently</div>
				@endif
			</div>
		</div>
	</div>
	<div class="container-fluid p-t-1">
		<h5 class>User details:</h5>
		<div class="table-responsive">
			<table id="userTable" class="table table-sm table-bordered">
				<thead class="thead-default">
					<tr>
						<th class="text-xs-center">ID</th>
						<th class="text-xs-center">Name</th>
						<th class="text-xs-center">Email</th>
						<th class="text-xs-center">Role</th>
						<th class="text-xs-center">Role Description</th>
						<th class="text-xs-center">-</th>
					</tr>
				</thead>
				<tbody class="text-xs-center">
					<tr>
						<?php
                        if ($user->hasRole('superAdmin|wlpTeam')) {
                            $projectNames = "All";
                        } else {
                            $projectNames = implode(", ", $user->projectNames());
                        }
                        ?>
                        <td>{{$user->id or 'N/A'}}</td>
                        <td>{{$user->name or 'N/A'}}</td>
                        <td>{{$user->email or 'N/A'}}</td>
                        <td>{{$user->role->name or 'N/A'}}</td>
                        <td>{{$user->role->description or 'N/A'}}</td>
                        <td><a class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#editRoleModal">Edit Role</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container-fluid p-t-1">
        <h5 class>Project permissions:</h5>
        <div class="table-responsive">
            <table id="projectsTable" class="table table-sm table-bordered">
                <thead class="thead-default">
                    <tr>
                        <th class="text-xs-center">ID</th>
                        <th class="text-xs-center">Name</th>
                        <th class="text-xs-center">Ident</th>
                        <th class="text-xs-center">-</th>
                    </tr>
                </thead>
                <tbody class="text-xs-center">
                
                @if (isset($projects) && count($projects) > 0)
                <?php $projectsWithAccess = array_map(function ($x) {
    return $x->id;
}, $user->projects()); ?>
                
                    @foreach ($projects as $key=>$project)
                    <?php $hasAccess = in_array($project->id, $projectsWithAccess); ?>
                    
                    <tr @if ($hasAccess) class="table-success" @endif>
                        <td>{{$project->id or 'N/A'}}</td>
                        <td>{{$project->name or 'N/A'}}</td>
                        <td>{{$project->ident or 'N/A'}}</td>
                        <td>
                            <form action="{{$hasAccess ? url('/admin/permissions/revoke') : url('/admin/permissions/grant')}}" method="POST">
                                {!! csrf_field() !!}
                                <input type="hidden" name="userId" value={{$user->id}}>
                                <input type="hidden" name="projectId" value={{$project->id}}>
                                
                                @if ($hasAccess)
                                <input type="submit" value="Revoke" class="btn btn-danger btn-sm">
                                @else 
                                <input type="submit" value="Grant" class="btn btn-success btn-sm">
                                @endif
                                
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                @else
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
