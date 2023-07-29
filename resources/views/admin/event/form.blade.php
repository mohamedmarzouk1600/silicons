@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title">
                {!! Form::open(['route' => isset($row->id) ? [$routePrefix.'update',$row->id]:$routePrefix.'store','method' => isset($row->id) ?  'PATCH' : 'POST','files' => true]) !!}
                    {{csrf_field()}}

                        <x-admin-form-group class="col-sm-6{!! formError($errors,'name',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("name", __("name").':') !!}
                                {!! Form::text("name",isset($row->id) ? $row->name:old("name"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"name") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-6{!! formError($errors,'description',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("description", __("description").':') !!}
                                {!! Form::textarea('description',isset($row->id) ? $row->description:old("description"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"description") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-6{!! formError($errors,'from_date',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("from_date", __("from_date").':') !!}
                                {!! Form::text("from_date",isset($row->id) ? $row->from_date:old("from_date"),['class'=>'form-control datetimepicker']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"from_date") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-6{!! formError($errors,'to_date',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("to_date", __("to_date").':') !!}
                                {!! Form::text("to_date",isset($row->id) ? $row->to_date:old("to_date"),['class'=>'form-control datetimepicker']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"to_date") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-6{!! formError($errors,'lat',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("lat", __("lat").':') !!}
                                {!! Form::number("lat",isset($row->id) ? $row->lat:old("lat"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"lat") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-6{!! formError($errors,'lng',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("lng", __("lng").':') !!}
                                {!! Form::number("lng",isset($row->id) ? $row->lng:old("lng"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"lng") !!}
                        </x-admin-form-group>



                        <div class="card-block card-dashboard">
                            {!! Form::submit($submit,['class'=>'btn btn-success pull-right']) !!}
                        </div>
                {!! Form::close() !!}
            </x-admin-card>
        </section>

    </div>
@endsection
