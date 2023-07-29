@extends('admin.layout')

@section('content')
    @push('css')
        <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css" rel="stylesheet">
        <style>
            .select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field {
                background: transparent;
                padding: 0 12px;
                height: 32px;
                line-height: 1.42857143;
                margin-top: 0;
                min-width: 100%;
            }
            .form-control.select2-hidden-accessible {
                display: none;
            }
            .select2-container--bootstrap .select2-results > .select2-results__options {
                max-height: 200px;
                background-color: #fff;
                min-width: 54rem;
            }
        </style>
    @endpush
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title">
                {!! Form::open(['route' => isset($row->id) ? [$routePrefix.'update',$row->id]:$routePrefix.'store','method' => isset($row->id) ?  'PATCH' : 'POST','files' => true]) !!}
                {{csrf_field()}}

                <x-admin-form-group class="col-sm-12{!! formError($errors,'fullname',true) !!}">
                    <x-admin-form-group-wrapper>
                        {!! Form::label('fullname', __('Admin fullname').':') !!}
                        {!! Form::text('fullname',isset($row->id) ? $row->fullname:old('fullname'),['class'=>'form-control']) !!}
                    </x-admin-form-group-wrapper>
                    {!! formError($errors,'fullname') !!}
                </x-admin-form-group>

                <x-admin-form-group class="col-sm-12{!! formError($errors,'email',true) !!}">
                    <x-admin-form-group-wrapper>
                        {!! Form::label('email', __('Email address (used for login)').':') !!}
                        {!! Form::text('email',isset($row->id) ? $row->email:old('email'),['class'=>'form-control']) !!}
                    </x-admin-form-group-wrapper>
                    {!! formError($errors,'email') !!}
                </x-admin-form-group>

                <x-admin-row>
                    <x-admin-form-group class="col-sm-6{!! formError($errors,'password',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('password', __('Password').':') !!}
                            {!! Form::password('password',['class'=>'form-control','autocomplete'=>'off']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'password') !!}
                    </x-admin-form-group>

                    <x-admin-form-group class="col-sm-6{!! formError($errors,'password_confirmation',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('password_confirmation', __('password confirmation').':') !!}
                            {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'password_confirmation') !!}
                    </x-admin-form-group>
                </x-admin-row>
                <x-admin-row>
                    <x-admin-form-group class="col-sm-6{!! formError($errors,'user_group',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('user_group', __('user_group').':') !!}
                            {!! Form::select('user_group',enum_select(\MaxDev\Enums\UserGroupType::class),isset($row->id) ? $row->user_group:old('user_group'),['class'=>'form-control','id'=>'user_group']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'user_group') !!}
                    </x-admin-form-group>

                    <x-admin-form-group class="col-sm-6{!! formError($errors,'status',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('status', __('Admin status').':') !!}
                            {!! Form::select('status',enum_select(\MaxDev\Enums\Status::class),isset($row->id) ? $row->status:old('status'),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'status') !!}
                    </x-admin-form-group>
                </x-admin-row>

                <input name='language' value='ar' type='hidden'>

                <!-- <x-admin-form-group class="col-sm-12{!! formError($errors,'language',true) !!}">
                    <x-admin-form-group-wrapper>
                        {!! Form::label('language', __('Default language').':') !!}
                        {!! Form::select('language',enum_select(\MaxDev\Enums\Language::class),isset($row->id) ? $row->language:old('language'),['class'=>'form-control']) !!}
                    </x-admin-form-group-wrapper>
                    {!! formError($errors,'language') !!}
                </x-admin-form-group> -->


                <div class="card-block card-dashboard">
                    {!! Form::submit($submit,['class'=>'btn btn-success pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </x-admin-card>
        </section>
        <!--/ Javascript sourced data -->
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
                $("#providerHas").hide();

            $( ".select2-multiple" ).select2({
                theme: "bootstrap",
                placeholder: "Select an item"
            });
            $("#user_group").change(function() {
                
                $("#providerHas").hide();
            })
        })
    </script>
@endpush
