@extends('admin.layout')

@section('content')
<x-admin-card :title="$title">
    {!! Form::model($row, ['method'=>'PATCH','route' => ['admin.profile']]) !!}

    <div class="form-group{{$errors->has('fullname') ? ' has-danger':''}} col-sm-6">
        {!! Form::label('fullname', __('fullname'),['class' => 'control-label']) !!}
        {!! Form::text('fullname',null,['class'=>'form-control']) !!}
        @if($errors->has('fullname'))
            <span class="help-block danger">
                            {{$errors->first('fullname')}}
                        </span>
        @endif
    </div>

    <div class="form-group{{$errors->has('email') ? ' has-danger':''}} col-sm-6">
        {!! Form::label('email', __('Username'),['class' => 'control-label']) !!}
        {!! Form::text('email',null,['class'=>'form-control','autocomplete'=>'off']) !!}
        @if($errors->has('email'))
            <span class="help-block danger">
                            {{$errors->first('email')}}
                        </span>
        @endif
    </div>


    <div class="form-group{{$errors->has('password') ? ' has-danger':''}} col-sm-6">
        {!! Form::label('password', __('Password'),['class' => 'control-label']) !!}
        {!! Form::password('password',['class'=>'form-control']) !!}
        @if($errors->has('password'))
            <span class="help-block danger">
                            {{$errors->first('password')}}
                        </span>
        @elseif(isset($row))
            <span class="help-block warning">{{__('Leave it empty and it won\'t be changed')}}</span>
        @endif
    </div>

    <div class="form-group{{$errors->has('password_confirmation') ? ' has-danger':''}} col-sm-6">
        {!! Form::label('password_confirmation', __('Password confirmation'),['class' => 'control-label']) !!}
        {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
        @if($errors->has('password_confirmation'))
            <span class="help-block danger">
                            {{$errors->first('password_confirmation')}}
                        </span>
        @endif
    </div>

    <button type="submit" class="btn btn-success btn-lg btn-flat fa fa-{{__('Save')}} pull-left mr-2"> {{__('Save')}}</button>
    <div class="clearfix"></div>
    <br>
    {!! Form::close() !!}
</x-admin-card>
@endsection
