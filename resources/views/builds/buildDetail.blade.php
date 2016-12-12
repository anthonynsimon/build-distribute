@extends('layouts.app') @section('mainView')
@if (isset($builds) && count($builds) > 0)
    @each('builds.build', $builds, 'build')
@else
<div class="card card-inverse card-danger">
    <div class="card-block">
        <h3 class="card-title">No build found</h3>
    </div>
</div>
@endif @endsection
