@extends('admin')

@section('content')

    <div class="page-header">
        <h1>
            Form Elements
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                Module name
            </small>
        </h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            {!! Form::open(['method' => 'post' , 'action' => ['Admin\NewsController@store'], 'class'=> 'form-horizontal', 'files' => true])  !!}

            @include('admin.news.form', ['submitButtontext' => 'Submit'])

            {!! Form::close()  !!}


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->

@stop