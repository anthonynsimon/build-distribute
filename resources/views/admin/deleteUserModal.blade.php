@section('deleteUserModal')
@if (isset($user))
<div class="bd-example">
  <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
          <h4 class="modal-title text-xs-center">Confirmation Required</h4>
        </div>
        <div class="modal-body text-xs-center">
			<form form action="{{url('/admin/users/'.$user->id)}}" method="POST" class="form-inline">
				{!! csrf_field() !!}
				<input name="_method" type="hidden" value="DELETE">
				
				<label><h4>Delete all records for the user with email <strong>{{$user->email}}</strong>?</h4></label>
				<label>You cannot revert this action.</label>
				<br>
				<input type="hidden" name="email" value={{$user->email}}>
				<input type="submit" value="Yes, delete this user" class="btn btn-danger btn-sm">					
			</form>
		</div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection