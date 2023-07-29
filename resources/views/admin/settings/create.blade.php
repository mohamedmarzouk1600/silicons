@extends('admin.layout')

@section('content')
    <x-admin-card :title="$title" :buttons="$buttons">
        {!! Form::open(['route' => isset($row->id) ? [$routePrefix.'update',$row->id]:$routePrefix.'store','method' => isset($row->id) ?  'PATCH' : 'POST']) !!}

        <x-admin-row>
            <x-admin-form-group class="col-sm-6{!! formError($errors,'groups',true) !!}">
                <x-admin-form-group-wrapper>
                    {!! Form::label('groups', __('Group').':') !!}
                    {!! Form::select('groups',$groups,isset($row->id) ? $row->groups:old('groups'),['class'=>'form-control','id'=>'groups']) !!}
                </x-admin-form-group-wrapper>
                {!! formError($errors,'groups') !!}
            </x-admin-form-group>

            <div class="form-group col-sm-6{!! formError($errors,'has_translations',true) !!}">
                <div class="controls">
                    {!! Form::label('has_translations', __('Setting has translations').':') !!}
                    {!! Form::select('has_translations',[1=>__('Yes'),0=>__('No')],isset($row->id) ? $row->has_translations:old('has_translations'),['class'=>'form-control']) !!}
                </div>
                {!! formError($errors,'has_translations') !!}
            </div>
        </x-admin-row>


        <x-admin-row>
            <x-admin-form-group class="col-sm-12{!! formError($errors,'name',true) !!}">
                <x-admin-form-group-wrapper>
                    {!! Form::label('name', __('Admin name').':') !!}
                    {!! Form::text('name',isset($row->id) ? $row->name:old('name'),['class'=>'form-control']) !!}
                </x-admin-form-group-wrapper>
                {!! formError($errors,'name') !!}
            </x-admin-form-group>
        </x-admin-row>

        @foreach(config('app.locales') as $locale)
            <x-admin-row>
            <div class="translations_{{$locale}}" style="display: @if(isset($row) && $row->has_translations == 0 && $locale != config('app.fallback_locale')) none; @endif">
                <div class="form-group col-sm-12{!! formError($errors,'value['.$locale.']',true) !!}">
                    <div class="controls {{$locale}}">
                        {!! Form::label('value['.$locale.']', __('Setting value',[],$locale).' ('.$locale.') :',['class'=>$locale]) !!}
                        {!! Form::textarea('value['.$locale.']',isset($row->id) ? $row->gettranslation('value',$locale):old('value['.$locale.']'),['class'=>'form-control']) !!}
                    </div>
                    {!! formError($errors,'value['.$locale.']') !!}
                </div>
            </div>
            </x-admin-row>
        @endforeach


        <button type="submit" class="btn btn-success btn-lg btn-flat fa fa-{{((isset($row))?'save':'plus')}} pull-right mr-2"> {{ (((isset($row) || isset($adminrow)) ? __('Save'):__('Add')))}}</button>
        <div class="clearfix"></div>
        <br>
        {!! Form::close() !!}
    </x-admin-card>
@endsection

@push('scripts')
    <script>
        $(function() {
            // console.log($('#has_translations').val());
            $('#has_translations').on('change',function(){
                var locales = ['{!! implode("' ,'",array_diff(config('app.locales'), [config('app.fallback_locale')])) !!}'];
                for (i = 0; i < locales.length; i++) {
                    className = locales[i];
                    $('.translations_'+className).toggle();
                }

            });
        });
    </script>
@endpush
