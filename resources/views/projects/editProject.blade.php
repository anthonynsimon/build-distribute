@extends('layouts.app') @section('mainView')
@if (isset($project))
<div class="container">
	
	<form class="form-signin soft-shadow" method="POST" action="{{ url('/projects/'.$project->name) }}">
		@cannot('adminOnly')
		<div class="alert alert-warning">
			<p class="text-muted text-xs-center">You are not authorized to edit this project.</p>
		</div>
		@endcannot
		{!! csrf_field() !!}
		<fieldset class="form-group">
			<input name="_method" type="hidden" value="PUT">

			<label class="sr-only">Project Name</label>
			
			<input type="name" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{$project->name}}" @cannot('adminOnly') readonly @endcannot>
			 @if ($errors->has('name'))
				<span class="help-block">
					<strong>{{ $errors->first('name') }}</strong>
				</span>
			@endif
			
			<input class="form-control" type="text" placeholder="Ident: {{$project->ident}}" readonly>
			<p class="text-muted text-xs-right">*Project ident cannot be modified</p>
			<br>
			<button type="submit" class="btn btn-lg btn-primary btn-block"  @cannot('adminOnly') disabled @endcannot>Save changes</button>
		</fieldset>
	</form>
</div>
@endif
@endsection