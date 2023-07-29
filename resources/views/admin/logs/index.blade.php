@extends('admin.layout')
@section('content')
    <div class="content-body">
        <section id="server-processing">
            <x-admin-card :title="$title">
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



<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{__('Filter')}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
                {!! Form::open(['onsubmit'=>'filterFunction($(this));return false;', 'class'=>"m-form m-form--fit m-form--label-align-right"]) !!}
                <div class="modal-body">
                    <div class="form-group m-form__group row m--margin-top-20">
                        {{ Form::label('created_at1',__('Created From'), ['class'=>"col-form-label col-lg-3 col-sm-12"]) }}
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <div class="input-group date" >
                                {!! Form::text('created_at1',null,['class'=>'form-control datepicker', ' placeholder'=>__('Select date')]) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        {{ Form::label('created_at2',__('Created To'), ['class'=>"col-form-label col-lg-3 col-sm-12"]) }}
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            <div class="input-group date" >
                                {!! Form::text('created_at2',null,['class'=>'form-control datepicker', ' placeholder'=>__('Select date')]) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        {{ Form::label('name',__('Name'), ['class'=>"col-form-label col-lg-3 col-sm-12"]) }}
                        <div class="col-lg-9 col-md-9 col-sm-12">
                            {!! Form::text('name',null,['class'=>'form-control ','id'=>'name', 'placeholder'=>__('Typing Name')]) !!}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        {{ Form::label('id',__('ID'), ['class'=>"col-form-label col-lg-3 col-sm-12"]) }}
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            {!! Form::text('id',null,['class'=>'form-control ','id'=>'id', 'placeholder'=>__('Enter ID Number'), 'min'=>1]) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-link m-btn btn-warning">
                        {{__('Reset Form')}}
                    </button>
                    <button type="button" class="btn btn-secondary m-btn" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                    <button type="submit" class="btn btn-brand btn-primary m-btn">
                        {{__('Filter')}}
                    </button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{app_asset('admin/css/jquery.dataTables.min.css')}}">
@endpush


@push('scripts')
    <script src="{{app_asset('admin/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
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