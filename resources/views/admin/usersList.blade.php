@extends('layouts.app') @section('mainView')
<div class="card soft-shadow">
	<div class="card-header text-white bg-primary">
		<div class="row">
			<div class="col-md-12">
				<label><h5>User Management</h5></label>
			</div>
		</div>
	</div>
	<div class="container-fluid p-t-1">
		<div class="table-responsive">
			<table id="usersTable" class="table table-striped table-sm table-bordered">
				<thead class="thead-default">
					<tr>
						<th class="text-xs-center">ID</th>
						<th class="text-xs-center">Name</th>
						<th class="text-xs-center">Email</th>
						<th class="text-xs-center">Role</th>
						<!--<th class="text-xs-center">Role Description</th>-->
						<th class="text-xs-center">Projects</th>
						<th class="text-xs-center"></th>
					</tr>
				</thead>
				<tbody class="text-xs-center">
				@if (isset($users) && count($users) > 0)
					@foreach ($users as $key=>$user)
					<tr>
						<?php
							if ($user->hasRole('superAdmin|wlpTeam')) {
								$projectNames = "All";
							}
							else {
								$projectNames = implode(", ", $user->projectNames());
							}
						?>
						<td>{{$user->id or 'N/A'}}</td>
						<td>{{$user->name or 'N/A'}}</td>
						<td>{{$user->email or 'N/A'}}</td>
						<td>{{$user->role->name or 'N/A'}}</td>
						<!--<td>{{$user->role->description or 'N/A'}}</td>-->
						<td>{{$projectNames or 'N/A'}}</td>
						<td><a href="{!!url('/admin/users/'.$user->id)!!}">Edit User</a></td>
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
@endsection