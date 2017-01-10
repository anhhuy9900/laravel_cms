{!! Form::hidden('id', $id) !!}

<div class="form-group">
	{!! Form::label('username', 'Username' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('username', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'username', 'placeholder'=> 'Username']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('email', 'Email' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('email', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'email', 'placeholder'=> 'Email']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('password', 'Password' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::password('password', ['class'  => 'col-xs-10 col-sm-5', 'data' => 'password', 'placeholder'=> 'Password']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('status', 'Status' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('status',  array( 0 => 'Unpulish', 1 => 'Pulish' ) , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('role_id', 'Role' , [ 'class' => 'col-sm-3 control-label no-padding-right']) !!}
	<div class="col-sm-2">
		{!! Form::select('role_id',  $list_roles , $result->role_id , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-9">

		{!! Form::submit($submitButtontext,  ['class'  => 'btn btn-info']) !!}

		&nbsp; &nbsp; &nbsp;
		<a href="{{ action('Admin\SystemUsersController@index') }}" class="btn" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Back
		</a>
	</div>
</div>
