@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">

            <x-admin-card :title="$title" :buttons="$buttons??''">
                    {!! Form::open(['route' => isset($row->id) ? [$routePrefix.'update',$row->id]:$routePrefix.'store','method' => isset($row->id) ?  'PATCH' : 'POST']) !!}

                <x-admin-row>
                    <x-admin-form-group class="col-sm-12{!! formError($errors,'name',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('name', __('Group name').':') !!}
                            {!! Form::text('name',isset($row->id) ? $row->name:old('name'),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'name') !!}
                    </x-admin-form-group>

                </x-admin-row>


                <x-admin-row>
                    <x-admin-form-group class="col-sm-4{!! formError($errors,'status',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('status', __('Group status').':') !!}
                            {!! Form::select('status',['0'=>__('In-active'),'1'=>__('Active')],isset($row->id) ? $row->status:old('status'),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'status') !!}
                    </x-admin-form-group>
                    <x-admin-form-group class="col-sm-4{!! formError($errors,'home_url',true) !!}">
                        <x-admin-form-group-wrapper>
                                {!! Form::label('home_url', __('Group home url').':') !!}
                                <select id="home_url" name="home_url" class="form-control">
                                    <option value="0">{{__('Select')}}</option>
                                    @foreach($permissions as $permissionGroup)
                                        <optgroup label="{{$permissionGroup['name']}}"></optgroup>
                                        @foreach($permissionGroup['permissions'] as $key=>$permission)
                                            <option value="{{$key}}"
                                                @php echo isset($row->id) ? ($row->home_url == $key) ? ' selected ':null:old('home_url') @endphp
                                            >{{$key}}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <br>
                                <input class="form-control" value="@php echo isset($row->id) ? $row->url_index:old('url_index') @endphp" type="number" name="url_index" id="url_index" style="display: none;">
                        </x-admin-form-group-wrapper>
                            {!! formError($errors,'home_url') !!}
                    </x-admin-form-group>

                    <x-admin-form-group class="col-sm-4{!! formError($errors,'user_group',true) !!}">
                        <x-admin-form-group-wrapper>
                            {!! Form::label('user_group', __('user_group').':') !!}
                            {!! Form::select('user_group',enum_select(\MaxDev\Enums\UserGroupType::class),isset($row->id) ? $row->user_group:old('user_group'),['class'=>'form-control']) !!}
                        </x-admin-form-group-wrapper>
                        {!! formError($errors,'user_group') !!}
                    </x-admin-form-group>
                </x-admin-row>

            </x-admin-card>

            <div class="row">
                @foreach($permissions as $permission)
                <x-admin-card :title="ucfirst($permission['name'])" class="col-sm-12 permissions">
                    <div class="col-sm-12">
                        <label class="pull-right">
                            <input type="checkbox" onclick="CheckPerms(this);">
                            {{__('Select all')}}
                        </label>
                    </div>
                    @foreach($permission['permissions'] as $key=>$val)
                        <label class="col-sm-12">
                            {!! Form::checkbox("permissions[]", "$key", isset($row->id) ? !array_diff($val,$currentpermissions) : false) !!}
                            {!! ucfirst(str_replace('-',' ',$key)) !!}

                            @if(Arr::exists(config('permissions-details'), $key))
                                <ul>
                                    <li>{{ Arr::get(config('permissions-details'), $key) }}</li>
                                </ul>
                            @endif
                        </label>
                        <div class="clearfix"></div>
                    @endforeach
                </x-admin-card>
                @endforeach
            </div>
            <x-admin-card :title="''" class="m-5">
                {!! Form::submit($submit,['class'=>'btn btn-success pull-right']) !!}
            </x-admin-card>
        </section>
    </div>
@endsection


@push('scripts')
    <script>
        function CheckPerms(perm) {
            var permissions = $(perm).parents('.permissions').find('input[type=\'checkbox\']');
            if($(perm).is(':checked')){
                $(permissions).prop('checked',true);
            } else {
                $(permissions).prop('checked',false);
            }
        }

        $(function(){
            CheckHomeUrl($('#home_url').val());
            $('#home_url').on('ready change',function(){
                $('input:checkbox[value="'+$('#home_url').val()+'"]').prop('checked',true)
                CheckHomeUrl($('#home_url').val());
            });
        });

        function CheckHomeUrl(val){
            if(val.indexOf('view-one') > -1 || val.indexOf('edit') > -1 || val.indexOf('delete') > -1)
                $('#url_index').show();
            else
                $('#url_index').hide();
        }

    </script>
@endpush
