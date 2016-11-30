@if (isset($build))
<div class="card soft-shadow">
    <div class="card-header text-white bg-primary">
        <div class="row">
            <div class="col-md-12">
                <label class="label">
                    <h5>
                        {!!$build->platform == 'android' ? '<i class="fa fa-android"></i>' : '<i class="fa fa-apple"></i>'!!}
                        Build # {{$build->buildNumber}}
                    </h5>
                </label>
                <div class="btn-group pull-xs-right">
                    @if (strtolower($build->platform) == 'android')
                    <a href="{!!url('/downloads/builds/'.$build->id)!!}"
                        class="btn btn-success btn-sm">
                        Install
                    </a>
                    @elseif (strtolower($build->platform) == 'ios')
                    <a href="itms-services://?action=download-manifest&url={!!url('/downloads/plist/'.ViewService::generateUrlSafeToken($build->id))!!}" 
                        class="btn btn-success btn-sm">
                        Install
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th>Revision</th>
                        <td>{{$build->revision or 'N/A'}}</td>
                    </tr>
                    <tr>
                        <th>Platform</th>
                        <td>{{$build->platform or 'N/A'}}</td>
                    </tr>
                    <tr>
                        <th>Bundle Identifier</th>
                        <td>{{$build->bundleIdentifier or 'N/A'}}</td>
                    </tr>
                    <tr>
                        <th>Version</th>
                        <td>{{$build->version or 'N/A'}}</td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <td>{{$build->id or 'N/A'}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="container-fluid">
        @if (Auth::user()->can('adminOnly'))
            <strong>Build note:</strong>
            <form method="POST" action="{{ url('/projects/' . $build->project_id . '/builds/' . $build->id . '/note') }}">
                {!! csrf_field() !!}
                <fieldset class="form-group">
                    <input name="_method" type="hidden" value="PATCH">

                    <textarea id="editableTextArea" readonly type="text" class="form-control notEditing" name="note" placeholder="Click to edit your build note...">{{$build->note}}</textarea>
                    
                    <br />
                    <button id="editableTextAreaSubmit" type="submit" hidden class="btn btn-sm btn-primary">Update note</button>
                    <button id="editableTextAreaCancel" type="button" hidden class="btn btn-sm btn-danger">Cancel changes</button>
                </fieldset>
            </form>
        @else
        <div class="alert alert-warning">
            <strong>Build note:</strong>
            <p class="wrap-text">{{empty($build->note) ? "No note available for this build." : $build->note}}</p>
        </div>
        @endif
    </div>

    <div class="card-footer text-muted">Received at {{date_format($build->created_at, 'G:i \o\n l jS F Y')}}</div>
    
</div>
@endif
