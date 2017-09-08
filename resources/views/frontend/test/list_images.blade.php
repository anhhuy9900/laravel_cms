@extends('frontend')

@section('content')
	<div class="list_images">
		@if ($results)
			@foreach ($results as $value)
				<img src='{{ $value }}'/>
			@endforeach
		@endif
	</div>
@stop