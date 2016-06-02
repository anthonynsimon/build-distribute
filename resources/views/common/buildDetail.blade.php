@extends('layouts.app') @section('mainView')
@if (isset($build) && count($build) > 0)
	<div class="card soft-shadow">
		<div class="card-header text-white bg-primary">
			<div class="row">
				<div class="col-md-12">
					<label><h5>Build # {{$build->buildNumber}}</h5></label>
					<div class="btn-group pull-xs-right">
						@if (strtolower($build->platform) == 'android')
						<a href="{!!url('/downloads/builds/'.$build->id)!!}"
							class="btn btn-success btn-sm">
							Install
						</a>
						@elseif (strtolower($build->platform) == 'iphone')
						<a href="itms-services://?action=download-manifest&url={!!url('/downloads/plist/'.ViewService::generateUrlSafeToken($build->id))!!}" 
							class="btn btn-success btn-sm">
							Install
						</a>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<br>
			<div class="table-responsive">
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<th>Revision</th>
							<td>{{$build->revision or 'N/A'}}</td>
						</tr>
						<tr>
							<th>Platform</th>
							<td>{{$build->platform or 'N/A'}}</td>
						</tr>
						<tr>
							<th>Bundle Identifier</th>
							<td>{{$build->bundleIdentifier or 'N/A'}}</td>
						</tr>
						<tr>
							<th>Version</th>
							<td>{{$build->version or 'N/A'}}</td>
						</tr>
						<tr>
							<th>ID</th>
							<td>{{$build->id or 'N/A'}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="card-footer text-muted">Received at {{date_format($build->created_at, 'G:i \o\n l jS F Y')}}</div>
	</div>
@else
<div class="card card-inverse card-danger">
	<div class="card-block">
		<h3 class="card-title">No build found</h3>
	</div>
</div>
@endif @endsection