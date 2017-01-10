{!! Form::hidden('id', $id) !!}

<div class="form-group">
	{!! Form::label('title', 'Title' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('title', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'title', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('status', 'Status' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('status',  array( 0 => 'Unpulish', 1 => 'Pulish' ) , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-9">

		{!! Form::submit($submitButtontext,  ['class'  => 'btn btn-info']) !!}

		&nbsp; &nbsp; &nbsp;
		<a href="{{ action('Admin\CategoriesNewsController@index') }}" class="btn" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Back
		</a>
	</div>
</div>
