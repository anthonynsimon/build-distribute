@extends('layouts.app') @section('mainView')
<div class="container">
	<form class="form-signin soft-shadow" method="POST" action="{{ url('/projects/') }}">
		{!! csrf_field() !!}
		<fieldset class="form-group">

			<label class="sr-only">Project Name</label>
			<input type="name" class="form-control" name="name" value="{{ old('name') }}" placeholder="Project name">
			 @if ($errors->has('name'))
				<span class="help-block">
					<strong>{{ $errors->first('name') }}</strong>
				</span>
			@endif
			
			<label class="sr-only">Project Ident</label>
			<input type="name" class="form-control" name="ident" value="{{ old('ident') }}" placeholder="Ident">
			 @if ($errors->has('ident'))
				<span class="help-block">
					<strong>{{ $errors->first('ident') }}</strong>
				</span>
			@endif
			<br>
			<button type="submit" class="btn btn-lg btn-primary btn-block">Create project</button>
		</fieldset>
	</form>
</div>
@endsection