{!! Form::hidden('id', $id) !!}

<div class="form-group">
	{!! Form::label('role_name', 'Role Name' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('role_name', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'role_name', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('role_status', 'Role Status' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('role_status',  array( 0 => 'Unpulish', 1 => 'Pulish' ) , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="form-group">
	<div class="col-sm-3 control-label no-padding-right">Lists Modules For Roles Type </div>
	<div class="col-sm-9">
		@if ($list_modules)
			@foreach ($list_modules as $module)
				<table border="1" cellpadding="0" cellspacing="0" width="20%" align="left" style="margin:0 3% 2% 0;">
					<tr>
						<td>{{ $module->module_name }}</td>
						<td></td>
					</tr>
					<tr>
						<td width="80%">View</td>
						<td width="30%">{!! Form::checkbox('role_type['.$module->id.'][view]', 1, $module->view, ['class'  => 'form-control', 'data' => '']) !!}</td>
					</tr>
					<tr>
						<td width="80%">Add New</td>
						<td width="30%">{!! Form::checkbox('role_type['.$module->id.'][add]', 1, $module->add, ['class'  => 'form-control', 'data' => '']) !!}</td>
					</tr>
					<tr>
						<td width="80%">Edit</td>
						<td width="30%">{!! Form::checkbox('role_type['.$module->id.'][edit]', 1, $module->edit, ['class'  => 'form-control', 'data' => '']) !!}</td>
					</tr>
					<tr>
						<td width="80%">Delete</td>
						<td width="30%">{!! Form::checkbox('role_type['.$module->id.'][delete]', 1, $module->delete, ['class'  => 'form-control', 'data' => '']) !!}</td>
					</tr>
				</table>
			@endforeach
		@endif
	</div>
</div>

<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-9">

		{!! Form::submit($submitButtontext,  ['class'  => 'btn btn-info']) !!}

		&nbsp; &nbsp; &nbsp;
		<a href="{{ action('Admin\SystemRolesController@index') }}" class="btn" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Back
		</a>
	</div>
</div>
