@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title">
                {!! Form::open(['route' => isset($row->id) ? [$routePrefix.'update',$row->id]:$routePrefix.'store','method' => isset($row->id) ?  'PATCH' : 'POST','files' => true]) !!}
                    {{csrf_field()}}

                        <x-admin-form-group class="col-sm-12{!! formError($errors,'qr',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("qr", __("qr").':') !!}
                                {!! Form::text("qr",isset($row->id) ? $row->qr:old("qr"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"qr") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'send_email',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("send_email", __("send_email").':') !!}
                                {!! Form::text("send_email",isset($row->id) ? $row->send_email:old("send_email"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"send_email") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'send_message',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("send_message", __("send_message").':') !!}
                                {!! Form::text("send_message",isset($row->id) ? $row->send_message:old("send_message"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"send_message") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'scan_qr',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("scan_qr", __("scan_qr").':') !!}
                                {!! Form::text("scan_qr",isset($row->id) ? $row->scan_qr:old("scan_qr"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"scan_qr") !!}
                        </x-admin-form-group>


                    <x-admin-form-group class="col-sm-6{!! formError($errors,'email_model_id',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label("email_model_id", __("email_model_id").':') !!}
                            {!! Form::select("email_model_id",$emailModel,isset($row->id) ? $row->email_model_id:old("email_model_id"),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,"email_model_id") !!}
                    </x-admin-form-group>


                    <x-admin-form-group class="col-sm-6{!! formError($errors,'contact_id',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label("contact_id", __("contact_id").':') !!}
                            {!! Form::select("contact_id",$contact,isset($row->id) ? $row->contact_id:old("contact_id"),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,"contact_id") !!}
                    </x-admin-form-group>



                        <div class="card-block card-dashboard">
                            {!! Form::submit($submit,['class'=>'btn btn-success pull-right']) !!}
                        </div>
                {!! Form::close() !!}
            </x-admin-card>
        </section>

    </div>
@endsection
