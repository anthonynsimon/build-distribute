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
						@elseif (strtolower($build->platform) == 'ios')
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
			<br />

			<div class="input-group">

				<form method="POST" action="{{ url('/tags') }}">
					{!! csrf_field() !!}
					<input type="text" class="form-control" name="name" placeholder="Create a new tag...">
					<button hidden class="btn btn-primary" type="submit">Create</button>
				</form>

				<div class="input-group-btn">
					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Assign existing tag
					</button>
					<div class="dropdown-menu dropdown-menu-right">
						@foreach ($availableTags as $key=>$tag)
						<form method="POST" action="{{ url('/builds/' . $build->id . '/tags') }}">
							{!! csrf_field() !!}
							<input name="tagId" value="{{$tag->id}}" hidden>
							<button class="dropdown-item" type="submit">{{$tag->name}}</button>
						</form>
						@endforeach
					</div>
				</div>
			</div>

			<br />

			@foreach ($buildTags as $key=>$tag)
			<form method="POST" action="{{ url('/builds/' . $build->id . '/tags/' . $tag->id) }}">
				{!! csrf_field() !!}
				<span class="label label-success">{{$tag->name}}
				<input name="_method" type="hidden" value="DELETE">
					<button class="btn-danger" type="submit">X</button>
				</span>
			</form>
			@endforeach
			
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

		<div class="container-fluid">
			<div class="alert alert-warning">
				<strong>Build note:</strong>
				@if (Auth::user()->can('adminOnly'))
					<form method="POST" action="{{ url('/projects/' . $projectId . '/builds/' . $build->id . '/note') }}">
						{!! csrf_field() !!}
						<fieldset class="form-group">
							<input name="_method" type="hidden" value="PATCH">

							<textarea id="editableTextArea" readonly type="text" class="form-control notEditing" name="note" placeholder="Click to edit your build note...">{{$build->note}}</textarea>
							
							<br />
							<button id="editableTextAreaSubmit" type="submit" hidden class="btn btn-sm btn-primary">Update note</button>
							<button id="editableTextAreaCancel" type="button" hidden class="btn btn-sm btn-danger">Cancel changes</button>
						</fieldset>
					</form>
				@else
					<p class="wrap-text">{{empty($build->note) ? "No note available for this build." : $build->note}}</p>
				@endif
			</div>
		</div>
		
	</div>
@else
<div class="card card-inverse card-danger">
	<div class="card-block">
		<h3 class="card-title">No build found</h3>
	</div>
</div>
@endif

@endsection