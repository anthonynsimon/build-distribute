@extends('layouts.app') @section('content')
<div class="container">
	<a href="{{ route('social.redirect', ['provider' => 'github']) }}" class="btn btn-lg btn-primary btn-block">Login with GitHub</a>
	
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
			<!--<p class="text-xs-center">
				<a class="text-muted" href="{{ url('/password/email') }}">Forgot Your Password?</a>
			</p>-->
		</fieldset>
	</form>
	<p class="text-xs-center">
		<a class="text-muted" href="{{ url('/register') }}">Not registered yet? Sign up here</a>
	</p>
</div>
@endsection