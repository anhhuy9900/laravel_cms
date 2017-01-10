{!! Form::hidden('id', $id) !!}

<div class="form-group">
	{!! Form::label('category_id', 'Choose category' , [ 'class' => 'col-sm-3 control-label no-padding-right']) !!}
	<div class="col-sm-2">
		{!! Form::select('category_id',  $list_categories , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('title', 'Title' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::text('title', null, ['class'  => 'col-xs-10 col-sm-5', 'data' => 'title', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('image', 'Image' , [ 'class' => 'col-sm-3 control-label no-padding-right']) !!}
	<div class="col-sm-9">
		{!! Form::file('file', null) !!}
		@if (!empty($result->image))
			<img src="{{ __resize_img($result->image, 100, 100) }}" width="100" height="100" />
		@endif
	</div>
</div>

<div class="form-group">
	{!! Form::label('description', 'Description' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::textarea('description', null, ['size' => '200x5', 'class'  => 'col-xs-10 col-sm-5', 'data' => 'description', 'placeholder'=> 'Value']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('content', 'Content' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-9">
		{!! Form::textarea('content', null, ['size' => '5x5', 'id'  => 'content-news',' class'  => 'col-xs-10 col-sm-5 ckeditor', 'data' => 'content']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tags', 'Tags' , [ 'class' => 'col-sm-3 control-label no-padding-right']) !!}
<div class="col-sm-9">
	<div class="inline">
		{!! Form::textarea('tags', null, ['size' => '200x5', 'class'  => 'col-xs-10 col-sm-5', 'data' => 'tags', 'placeholder'=> 'Enter tags ...']) !!}
		<textarea class="list_tags hide">{{ $list_tags }}</textarea>
	</div>
</div>
</div>

<div class="form-group">
	{!! Form::label('status', 'Status' , [ 'class' => 'col-sm-3 control-label no-padding-right required']) !!}
	<div class="col-sm-2">
		{!! Form::select('status',  array( 0 => 'Unpulish', 1 => 'Pulish' ) , null , ['class'  => 'form-control', '']) !!}
	</div>
</div>

<div class="form-group">
	<label class="col-sm-3 control-label no-padding-right">Galleries</label>
	<div class="col-sm-7">
		<div class="widget-box">
			<div class="widget-header">
				<h4 class="widget-title">Lists Images</h4>

				<div class="widget-toolbar">
					<a href="#" id="multi_uploads" class="btn">Select files</a>
					<a href="#" data-action="collapse">
						<i class="ace-icon fa fa-chevron-up"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-main">
					{!! Form::textarea('lists_thumb', (!empty($result->list_files_gallery ) ? json_encode($result->list_files_gallery) : null) , [' class'  => 'hide lists_thumb', 'data' => 'lists_thumb']) !!}
					{!! Form::textarea('lists_del_file', null , [' class'  => 'hide lists_del_file', 'data' => 'lists_del_file']) !!}
					<div class="form-group" id="filelist">
						@if (!empty($result->list_files_gallery))
							@foreach ($result->list_files_gallery as $value)
								<div class="thumb_uploaded">
									<img src="{{ url().$value->file }}" class="img_uploaded" />
									<i class="icon-delete" data-value="{{ $value->file }}" data-id="{{ $value->id }}"></i>
								</div>
							@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-9">

		{!! Form::submit($submitButtontext,  ['class'  => 'btn btn-info']) !!}

		&nbsp; &nbsp; &nbsp;
		<a href="{{ action('Admin\NewsController@index') }}" class="btn" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Back
		</a>
	</div>
</div>


<link href="{{ asset('public/admin/js/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css') }}" rel="stylesheet">
<script type="text/javascript">
	$(function() {
		AdminMain.Func._plupload_files("{{ action('Admin\AdminController@upload_files') }}", 'filelist', 'multi_uploads');
	});
</script>
