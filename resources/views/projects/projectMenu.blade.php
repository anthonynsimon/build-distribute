@section('projectMenu')
<ul class="list-group soft-shadow">
	<li class="list-group-item bg-primary">
		<h5 class="text-xs-left">Projects</h5>
	</li>
	@can('adminOnly')
	<a href="{{url('/projects/create')}}" class="list-group-item">
		<i class="fa fa-plus"></i> Create project
	</a>
	@endcan
	
	<?php $allowedProjects = ViewService::getAllowedProjects(); ?>
	@if (isset($allowedProjects) && count($allowedProjects) > 0)
		@foreach ($allowedProjects as $project)
		<a href="{{url('/projects/'.$project->name)}}" class="list-group-item {{Request::is('projects/'.$project->name.'*') || Request::is('projects/'.$project->id.'/*') ? 'active-inverse' : ''}}">
			<span class="label label-default label-pill pull-xs-right">{{$project->builds()->count()}}</span>
			{{$project->name or 'Unkown Project'}}
		</a>
		@endforeach
	@else
	<li class="list-group-item">
		No Projects assigned...
	</li>
	@endif
</ul>
@endsection