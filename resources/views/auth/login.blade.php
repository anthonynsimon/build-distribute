@extends('layouts.app') @section('content')
<div class="container">
	<form class="form-signin soft-shadow" method="POST" action="{{ url('/login') }}">
		{!! csrf_field() !!}
		<fieldset class="form-group">
			<label class="sr-only">E-Mail Address</label>

			<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" required autofocus> @if ($errors->has('email'))
			<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span> @endif

			<label class="sr-only">Password</label>

			<input type="password" class="form-control" name="password" placeholder="Password" required> @if ($errors->has('password'))
			<span class="help-block">
								<strong>{{ $errors->first('password') }}</strong>
							</span> @endif
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
	<div class="form-signin soft-shadow"
		<fieldset class="form-group">
			<a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn btn-lg btn-primary btn-block">
				Login with Google
			</a>
		</fieldset>
	</div>
	<br />
	<p class="text-xs-center">
		<a class="text-muted" href="{{ url('/register') }}">Not registered yet? Sign up here</a>
	</p>
</div>
@endsection