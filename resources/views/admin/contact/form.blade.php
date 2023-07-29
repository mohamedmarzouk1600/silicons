@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title">
                {!! Form::open(['route' => isset($row->id) ? [$routePrefix.'update',$row->id]:$routePrefix.'store','method' => isset($row->id) ?  'PATCH' : 'POST','files' => true]) !!}
                    {{csrf_field()}}

                        <x-admin-form-group class="col-sm-12{!! formError($errors,'email',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("email", __("email").':') !!}
                                {!! Form::text("email",isset($row->id) ? $row->email:old("email"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"email") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'phone',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("phone", __("phone").':') !!}
                                {!! Form::text("phone",isset($row->id) ? $row->phone:old("phone"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"phone") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'type',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("type", __("type").':') !!}
                                {!! Form::text("type",isset($row->id) ? $row->type:old("type"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"type") !!}
                        </x-admin-form-group>


                    <x-admin-form-group class="col-sm-6{!! formError($errors,'event_id',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label("event_id", __("event_id").':') !!}
                            {!! Form::select("event_id",$event,isset($row->id) ? $row->event_id:old("event_id"),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,"event_id") !!}
                    </x-admin-form-group>



                        <div class="card-block card-dashboard">
                            {!! Form::submit($submit,['class'=>'btn btn-success pull-right']) !!}
                        </div>
                {!! Form::close() !!}
            </x-admin-card>
        </section>

    </div>
@endsection
