{!! Form::hidden('id', $id) !!}

<div class="form-group">
	{!! Form::label('type', 'Choose Type' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('type',  array( 'textfield' => 'Textfield', 'textarea' => 'Textarea', 'editor' => 'Editor') , null , ['id' => 'property-type', 'class'  => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('key', 'Key' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('key', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'key', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('value', 'Value' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{{ $form_element_type }}
	</div>
</div>

<div class="form-group">
	{!! Form::label('status', 'Status' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('status',  array( 0 => 'Unpulish', 1 => 'Pulish' ) , null , ['class'  => 'form-control']) !!}
	</div>
</div>

<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-9">

		{!! Form::submit($submitButtontext,  ['class'  => 'btn btn-info']) !!}

		&nbsp; &nbsp; &nbsp;
		<a href="{{ action('Admin\PropertyDataController@index') }}" class="btn" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Back
		</a>
	</div>
</div>
