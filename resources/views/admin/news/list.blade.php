@extends('admin')

@section('content')

<div class="page-header col-xs-12">
	<div class="col-xs-6">
		<h1>
			Table
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				{{ $module_title }}
			</small>
		</h1>

		@include('admin.messages.success')

	</div>
	<div class="col-xs-1 pull-right span6">
		<a href="{{ action('Admin\NewsController@create') }}" class="btn btn-app btn-primary no-radius">
			<i class="ace-icon fa fa-pencil-square-o bigger-160"></i>
			Add New
		</a>
	</div>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="row">

			<div class="col-xs-12">

				<!-- div.table-filter -->
				<div id="accordion" class="accordion-style1 panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									<i class="ace-icon fa fa-angle-down bigger-110" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
									&nbsp;Filter Options
								</a>
							</h4>
						</div>

						<div class="panel-collapse collapse in" id="collapseOne">
							<div class="panel-body">
								<div class="row">

									<div class="row">
										<div class="col-xs-6 hr4">
											<div class="col-xs-6">
												<div class="tableTools-container">
													<div class="btn-group">
														<button data-toggle="dropdown" class="btn btn-primary btn-white dropdown-toggle" aria-expanded="false">
															Report
															<i class="ace-icon fa fa-angle-down icon-on-right"></i>
														</button>

														<ul class="dropdown-menu">
															<li>
																<a href="javascript:;" class="ad-export-data" data-type="excel">Export Excel</a>
															</li>

														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>

									<form name="filter_options" id="filter_options" action="{{ action('Admin\NewsController@index') }}">
										<div class="row">
											<div class="col-xs-6 hr4">
												<div class="dataTables_length" id="dynamic-table_length">
													<label class="col-xs-3">Display records</label>
													<div class="input-group col-xs-2">
														<select name="lm" aria-controls="dynamic-table" class="form-control input-sm" id="show_record_num">
															<option value="10">10</option>
															<option value="25">50</option>
															<option value="100">100</option>
														</select>
													</div>
												</div>
											</div>
										</div>


										{!! $filter_options !!}

										<div class="row">
											<div class="col-xs-6 hr4">
												<div class="col-xs-6">
													<button class="btn btn-primary search-filter">
														<i class="ace-icon fa fa-flask align-top bigger-125"></i>
														Search
													</button>
												</div>
											</div>
										</div>

									</form>

								</div>
							</div>
						</div>
					</div>

				</div>

				<!-- div.dataTables_borderWrap -->
				<div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
					<table id="dynamic-table" class="table table-striped table-bordered table-hover dataTable no-footer">
						<thead>
						<tr role="row">
								<th class="center sorting_disabled" rowspan="1" colspan="1" aria-label="">
									<label class="pos-rel">
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
									</label>
								</th>
								<th>Category</th>
								<th>Title</th>
								<th>Image</th>
								<th class="hidden-480">Status</th>
								<th class="{{ app('request')->input('order') ? app('request')->input("order") =='updated_date|DESC' ? 'sorting_desc' : 'sorting_asc' : 'sorting' }} admin_order_field" data-url="{{ App\Helpers\AdminHelpers::admin_render_data_url_order(app('request')->input("order")) }}">
									<i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
									Updated date
								</th>
								<th>Action</th>
							</tr>
						</thead>

						@if ($results)
						<tbody>
							@foreach ($results as $value)
							<tr role="row">
								<td class="center">
									<label class="pos-rel">
										<input type="checkbox" class="ace" />
										<span class="lbl"></span>
									</label>
								</td>
								<td>{{ $value->category_title }}</td>
								<td>{{ $value->title }}</td>
								<td class="center"><img src="{{ __resize_img($value->image, 100, 100) }}" width="100" height="100" /></td>
								<td class="hidden-480">
									<span class="label label-sm {{ $value->status ? 'label-success' : 'label-warning' }}">{{ $value->status ? 'Publish' : 'Unpublish' }}</span>
								</td>
								<td>{{ date('Y-m-d H:i:s',$value->updated_date) }}</td>
								<td>
									<div class="hidden-sm hidden-xs action-buttons">
										<a class="btn btn-xs btn-info" href="{{ action('Admin\NewsController@edit', ['id' => $value->id]) }}" title="Edit">
											<i class="ace-icon fa fa-pencil bigger-120"></i>
										</a>

										{!! Form::open([
											'method' => 'DELETE',
											'action' => ['Admin\NewsController@destroy', $value->id],
											'id' => 'form-delete-item-'.$value->id,
											'class' => 'form-delete'
										])  !!}
										<a class="btn btn-xs btn-danger submit-delete" href="javascript:;" title="Delete" data-id="{{ $value->id  }}">
											<i class="ace-icon fa fa-trash-o bigger-120"></i>
										</a>
										{!! Form::close()  !!}
									</div>

									<div class="hidden-md hidden-lg">
										<div class="inline pos-rel">
											<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
												<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
											</button>

											<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
												<li>
													<a href="#" class="tooltip-info" data-rel="tooltip" title="View">
														<span class="blue">
															<i class="ace-icon fa fa-search-plus bigger-120"></i>
														</span>
													</a>
												</li>

												<li>
													<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
														<span class="green">
															<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
														</span>
													</a>
												</li>

												<li>
													<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
														<span class="red">
															<i class="ace-icon fa fa-trash-o bigger-120"></i>
														</span>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</td>
							</tr>
							@endforeach
						</tbody>
						@endif
					</table>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6">

					<!--Pagination -->
					{!! $pagination !!}
					<!--END Pagination -->

				</div>
			</div>
		</div>

	</div><!-- /.col -->
</div><!-- /.row -->

@stop