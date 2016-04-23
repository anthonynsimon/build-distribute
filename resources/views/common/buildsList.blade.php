@extends('layouts.app') @section('mainView')
@if (isset($project))
<div class="card soft-shadow">
	<div class="card-header card-inverse bg-primary">
		<div class="row">
			<div class="col-md-12">
				<div class="btn-group p-l-1 pull-xs-right">
					<label><a href="{{url('projects/'.$project->name.'/edit')}}" class="btn btn-secondary-outline btn-sm white-outline">Edit Project</a></label>
				</div>
				<div id="platformRadio" class="btn-group pull-xs-right" data-toggle="buttons">
					<label class="btn btn-secondary-outline btn-sm white-outline active">
						<input type="radio" name="options" id="all" autocomplete="off" checked>All</input>
					</label>
					<label class="btn btn-secondary-outline btn-sm white-outline ">
						<input type="radio" name="options" id="iphone" autocomplete="off">iOS</input>
					</label>
					<label class="btn btn-secondary-outline btn-sm white-outline ">
						<input type="radio" name="options" id="android" autocomplete="off">Android</input>
					</label>
				</div>
				<h5 class="text-xs-left">Builds</h5>
			</div>			
		</div>	
	</div>

	<div class="container-fluid p-t-1">
		<div class="table-responsive">
			<table id="buildsTable" class="table table-striped table-sm table-bordered">
				<thead class="thead-default">
					<tr>
						<th class="text-xs-center">#</th>
						<th class="text-xs-center">Revision</th>
						<th class="text-xs-center">Platform</th>
						<th class="text-xs-center"></th>
						<th class="text-xs-center"></th>
					</tr>
				</thead>
				<tbody class="text-xs-center">
				@if (isset($builds) && count($builds) > 0)
					@foreach ($builds as $key=>$build)
					<?php $buildPlatform = strtolower($build->platform); ?>
					<tr id="{{$buildPlatform}}">
						<td>{{$build->buildNumber or 'N/A'}}</td>
						<td>{{$build->revision or 'N/A'}}</td>
						<td>
							{!!$buildPlatform == 'android' ? '<i class="fa fa-android"></i>' : '<i class="fa fa-apple"></i>'!!}
						</td>
						<td><a href="{{url('/builds/'.$build->id)}}">Details</a></td>
						<td>
							@if ($buildPlatform == 'android')
							<a class="btn btn-success btn-sm" href="{!!url('/downloads/builds/'.$build->id)!!}">Install</a>
							@elseif ($buildPlatform == 'iphone')
							<a class="btn btn-success btn-sm" href="itms-services://?action=download-manifest&url={!!url('/downloads/plist/'.ViewService::generateUrlSafeToken($build->id))!!}">Install</a>
							@endif
						</td>
					</tr>
					@endforeach
				@else
					<tr>
						<td>-</td>
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
@else
<div class="alert alert-warning" role="alert">
	<h5><strong>Hint:</strong></h5>
	 If you don't see any projects in the selection menu, most likely you haven't been authorized yet. If you think is is a mistake please contact us.
</div>
@endif
@endsection