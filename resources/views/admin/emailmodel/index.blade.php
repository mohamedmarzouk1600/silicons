@extends('admin.layout')
@section('content')
    <div class="content-body">
        <section id="server-processing">
            <x-admin-card :title="$title" :buttons="$buttons">
                <div class="card-block card-dashboard">
                    <table style="text-align: center;" id="datatable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            @foreach($tableColumns as $key => $value)
                                <th>{{$value}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            @foreach($tableColumns as $key => $value)
                                <th>{{$value}}</th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </x-admin-card>
        </section>
    </div>



<x-admin-filter-modal>
    <x-admin-row>
        <x-admin-form-group class="col-sm-6">
            <x-admin-form-group-wrapper>
                {!! Form::label('created_at1',__('Created From'))  !!}
                {!! Form::text('created_at1',null,['class'=>'form-control datepicker', ' placeholder'=>__('Select date')]) !!}
            </x-admin-form-group-wrapper>
        </x-admin-form-group>

        <x-admin-form-group class="col-sm-6">
            <x-admin-form-group-wrapper>
                {!! Form::label('created_at2',__('Created To')) !!}
                {!! Form::text('created_at2',null,['class'=>'form-control datepicker', ' placeholder'=>__('Select date')]) !!}
            </x-admin-form-group-wrapper>
        </x-admin-form-group>
    </x-admin-row>

    <x-admin-form-group>
        <x-admin-form-group-wrapper>
            {!! Form::label('id',__('ID')) !!}
            {!! Form::number('id',null,['class'=>'form-control ','id'=>'id', 'placeholder'=>__('Enter ID Number'), 'min'=>1]) !!}
        </x-admin-form-group-wrapper>
    </x-admin-form-group>
                        <x-admin-form-group class="col-sm-12{!! formError($errors,'name',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("name", __("name").':') !!}
                                {!! Form::text("name",isset($row->id) ? $row->name:old("name"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"name") !!}
                        </x-admin-form-group>


                        <x-admin-form-group class="col-sm-12{!! formError($errors,'description',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("description", __("description").':') !!}
                                {!! Form::text("description",isset($row->id) ? $row->description:old("description"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"description") !!}
                        </x-admin-form-group>

                        <x-admin-form-group class="col-sm-12{!! formError($errors,'event_id',true) !!}">
                            <x-admin-form-group-wrapper>
                                {!! Form::label("event_id", __("event_id").':') !!}
                                {!! Form::select("event_id",$event,isset($row->id) ? $row->event_id:old("event_id"),['class'=>'form-control']) !!}
                            </x-admin-form-group-wrapper>
                            {!! formError($errors,"event_id") !!}
                        </x-admin-form-group>



</x-admin-filter-modal>


@endsection


@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/jquery.dataTables.min.css')}}">
@endpush


@push('scripts')
    <script src="{{asset('admin/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $dataTableVar = $('table#datatable').DataTable({
            language: {
                url: '{{asset('admin/js/datatables/language/'.app()->getLocale().'.json')}}'
            },
            "iDisplayLength": 25,
            processing: true,
            serverSide: true,
            columns: [
                @foreach($Cols as $col)
                    { "data": "{{strtolower($col)}}" },
                @endforeach
            ],
            // "order": [[ 'id', "desc" ]],
            "order": [[ 0, "desc" ]],
            "ajax": {
                "url": "{{url()->full()}}",
                "type": "GET",
                "data": function(data){
                    data.isDataTable = "true";
                }
            },
            "fnPreDrawCallback": function(oSettings) {
                for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                    if(oSettings.aoData[i]._aData['status'] != ''){
                        // oSettings.aoData[i].nTr.className = oSettings.aoData[i]._aData['status'];
                    }
                }
            }

        });

        function filterFunction($this){
            if($this == false) {
                $url = '{{url()->full()}}?isDataTable=true';
            }else {
                $url = '{{url()->full()}}?isDataTable=true&'+$this.serialize();
            }

            $dataTableVar.ajax.url($url).load();
            $('#filter-modal').modal('hide');
        }

    </script>

@endpush
