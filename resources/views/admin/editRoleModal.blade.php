@section('editRoleModal')
@if (isset($user))
<div class="bd-example">
  <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
          <h4 class="modal-title text-xs-center">Editing Role for {{$user->email}}</h4>
        </div>
        <div class="modal-body text-xs-center">
			<form form action="{{url('/admin/users/'.$user->id.'/role')}}" method="POST" class="form-inline">
				{!! csrf_field() !!}
				<label class>Role: </label>
				<div class="form-group">
					<select class="form-control" name="roleId">
						@if (isset($roles) && count($roles) > 0)
						@foreach ($roles as $key=>$role)
						<option value="{{$role->id}}" @if ($user->role->name === $role->name) selected @endif > 
							{{$role->label}} | {{$role->description}}
						</option>
						@endforeach
						@endif
					</select>
				</div>
				<input type="hidden" name="userId" value={{$user->id}}>
				<button type="submit" class="btn btn-success" type="button">Save changes</button>
			</form>
		</div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection