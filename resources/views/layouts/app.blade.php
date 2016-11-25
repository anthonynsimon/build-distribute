<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<title>Build Distribution</title>

	<!-- Fonts -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">

	<!-- Styles -->
	<link rel="stylesheet" href="{{url('css/normalize.css')}}">
	<link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{url('css/styles.css')}}">

	<!-- CSS Addons -->
	<link rel="stylesheet" href="{{url('css/pushy.css')}}">

</head>

<body id="app-layout">

	<!-- Pushy Menu -->
	<nav class="pushy pushy-right">
		<ul class="list-group">
			@if (Auth::guest())
			<li>
				<p>You are currently not signed in</p>
			</li>
			<li class="pushy-link m-b-1">
				<a class="btn btn-secondary-outline white-outline" role="button" href="{{ url('/login') }}">Login</a>
			</li>
			<br>
			<li class="pushy-link m-b-1">
				<a class="btn btn-secondary-outline white-outline" role="button" href="{{ url('/register') }}">Register</a>
			</li>
			@else
			<li>
				<p>Logged in as {{Auth::user()->name}}</p>
			</li>
			<li class="pushy-link m-b-1">
				<a class="btn btn-secondary-outline white-outline" href="{{ url('/projects') }}">Projects</a>
			</li>
			@can('adminOnly')
			<li class="pushy-link m-b-1">
				<a class="btn btn-secondary-outline white-outline" href="{{ url('/admin/users') }}">Manage Users</a>
			</li>
			<!--<li class="pushy-link m-b-1">
				<a class="btn btn-secondary-outline white-outline" href="{{ url('/admin/projects') }}">Project Permissions</a>
			</li>-->
			@endcan
			<li class="pushy-link m-b-1">
				<a class="btn btn-secondary-outline white-outline" href="{{ url('/logout') }}">Logout</a>
			</li>
			@endif
		</ul>
	</nav>

	<!-- Site Overlay -->
	<div class="site-overlay"></div>

	<!-- Your Content -->
	<nav class="navbar navbar-light bg-faded no-border-bottom">
		<div class="nav navbar-nav pull-xs-left">
			<a class="navbar-brand" href="/">
				<span class="hidden-xs-down"><strong>Wolpertinger Games</strong> | </span>
				Build Distribution
			</a>
		</div>
		<a class="btn active pushy-enable-btn pull-xs-right">&#9776; Menu</a>
	</nav>
	<ul class="breadcrumb no-border-top soft-shadow">
		<li><a href="{{url('/')}}">Home</a></li>
		<?php $namedResources = ViewService::getPathNamedResources(); ?>
        @foreach ($namedResources as $index => $resourceName)
            @if ($index === count($namedResources) -1)
            <li class="active">{{ucfirst( $resourceName )}}</li>
            @else
            <li><a href="{{url(implode('/', array_slice($namedResources, 0, $index + 1)))}}">{{ucfirst( $resourceName )}}</a></li>
            @endif
        @endforeach
    </ul>

    <div >
    @if (Auth::guest())
        <div class="col-md-12 single-content">
            @yield('content')
        </div>
    @elseif (!Auth::guest() && Request::is('admin/*'))
        <div class="col-md-12">
            @yield('mainView')
        </div>
    @else
        <div class="col-md-4 m-b-1">
            @include('shared.projectMenu') @yield('projectMenu')
        </div>
        <div class="col-md-8">
            @yield('mainView')
        </div>
    @endif
    </div>


    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
    <script src="{{url('js/pushy.min.js')}}"></script>
    <script src="{{url('js/filterTable.js')}}"></script>
    <script src="{{url('js/clickToEdit.js')}}"></script>
    <script src="{{url('js/textAutosize.js')}}"></script>
</body>

</html>
