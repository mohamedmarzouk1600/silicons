@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title">
                {!! Form::open(['route' => $routePrefix.'import','method' => 'POST' ,'files' => true]) !!}
                    {{csrf_field()}}

                        <x-admin-form-group class="col-sm-12{!! formError($errors,'description',true) !!}">
                            <x-admin-form-group-wrapper>
                                <input type="hidden" name="event_id" value="{{$row->id}}">
                                <label class="control-label">Upload file</label>
                                <input type="file" class="form-control" name="upload_file">
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"description") !!}
                        </x-admin-form-group>



                        <div class="card-block card-dashboard">
                            {!! Form::submit($submit,['class'=>'btn btn-success pull-right']) !!}
                        </div>
                {!! Form::close() !!}
            </x-admin-card>
        </section>

    </div>
@endsection
