@extends('layouts.app') @section('content')
<div class="container">
	<form class="form-signin soft-shadow" method="POST" action="{{ url('/login') }}">
		{!! csrf_field() !!}

		@if ($errors->has('oauth2_message'))
		<div class="alert alert-danger text-xs-center" role="alert">
			<strong>{{ $errors->first('oauth2_message') }}</strong>
		</div>
		@endif

		<fieldset class="form-group">
			<label class="sr-only">E-Mail Address</label>
			<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>
			@if ($errors->has('email'))
			<span class="help-block">
				<strong>{{ $errors->first('email') }}</strong>
			</span>
			@endif

			<label class="sr-only">Password</label>
			<input type="password" class="form-control" name="password" placeholder="Password" required>
			@if ($errors->has('password'))
			<span class="help-block">
				<strong>{{ $errors->first('password') }}</strong>
			</span>
			@endif
			<div class="checkbox">
				<label>
					<input type="checkbox" name="remember"> Remember Me
				</label>
			</div>

			<button type="submit" class="btn btn-lg btn-primary btn-block">Login</button>
			<br>
		</fieldset>
	</form>

	<p class="text-xs-center text-muted">or</p>

	<div class="provider-signin">
		<a id="signin-google-link" class="img-fluid" href="{{ route('social.redirect', ['provider' => 'google']) }}">
			<img src="/img/btn_google_signin_light_normal_web@2x.png" class="img-fluid"></img>
		</a>
	</div>
	<br />
	<p class="text-xs-center">
		<a class="text-muted" href="{{ url('/register') }}">Not registered yet? Sign up here</a>
	</p>

</div>
@endsection