@extends('layouts.app') @section('mainView')
@if (isset($projects) && count($projects) > 0)
@foreach ($projects as $key=>$project)
@include('admin.grantAccessModal') @yield('grantAccessModal'.$project->id)
<div class="card">
	<div class="card-header text-white card-inverse card-primary">
		<div class="row">
			<div class="col-md-12">
				<label><h5>{{$project ? $project->name : 'Unkown Project'}} | ident: {{$project ? $project->ident : 'N/A'}}</h5></label>
				<div class="btn-group pull-xs-right">
					<a href="#"  class="btn btn-secondary-outline btn-sm white-outline"  data-toggle="modal" data-target="#grantAccessModal{{$project->id}}">
						Access Control
					</a>
					<a href="{!!url('/projects/'.$project->name.'/edit')!!}"  class="btn btn-secondary-outline btn-sm white-outline">
						Edit Project
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid p-t-1">
		<div class="btn-group pull-xs-right">

		</div>
		<div class="table-responsive m-t-1">
			<table id="usersTable" class="table table-striped table-sm table-bordered">
				<thead>
					<tr>
						<th class="text-xs-center">Name</th>
						<th class="text-xs-center">Email</th>
						<th class="text-xs-center">Role</th>
						<th class="text-xs-center">Role Description</th>
					</tr>
				</thead>
				<tbody class="text-xs-center">
				<?php $projectUsers = $project->users(); ?>
                @if (isset($projectUsers) && count($projectUsers) > 0)
                @foreach ($projectUsers as $key=>$user)
                    <tr>
                        <td>{{$user ? $user->name : 'N/A'}}</td>
                        <td>{{$user ? $user->email : 'N/A'}}</td>
                        <td>-</td>
                        <td>-</td>
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
@endforeach
@endif
@endsection
