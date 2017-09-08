{!! Form::hidden('id', $id) !!}

<div class="form-group">
	{!! Form::label('parent_id', 'Choose parent' , [ 'class' => 'col-sm-3 control-label no-padding-right']) !!}
	<div class="col-sm-2">
		{!! Form::select('parent_id',  $list_modules , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('module_name', 'Module Name' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('module_name', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'module_name', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('module_alias', 'Module Alias' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('module_alias', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'module_alias', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('module_order', 'Module Order' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('module_order', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'module_order', 'placeholder'=> '0']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('module_status', 'Module Status' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('module_status',  array( 0 => 'Unpulish', 1 => 'Pulish' ) , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-9">

		{!! Form::submit($submitButtontext,  ['class'  => 'btn btn-info']) !!}

		&nbsp; &nbsp; &nbsp;
		<a href="{{ action('Admin\SystemModulesController@index') }}" class="btn" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Back
		</a>
	</div>
</div>
