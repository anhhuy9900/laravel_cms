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

                {!! Form::model( $result, ['method' => 'PATCH' , 'action' => ['Admin\NewsController@update', $result->id], 'class'=> 'form-horizontal', 'files' => true])  !!}

                @include('admin.news.form', ['submitButtontext' => 'Update'])

                {!! Form::close()  !!}

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->


@stop