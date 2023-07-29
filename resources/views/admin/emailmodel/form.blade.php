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

                        <x-admin-form-group class="col-sm-6{!! formError($errors,'event_id',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("event_id", __("event_id").':') !!}
                                {!! Form::select("event_id",$event,isset($row->id) ? $row->event_id:old("event_id"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"event_id") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'description',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("description", __("description").':') !!}
                                {!! Form::textarea('description',isset($row->id) ? $row->description:old("description"),['class'=>'form-control']) !!}
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

@push('scripts')
<script src="{{asset('dashboard/js/tinymce.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">

    tinymce.init({
      selector: 'textarea',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ]
    });
</script>

@endpush
