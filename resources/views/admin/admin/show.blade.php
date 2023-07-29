@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title" :buttons="$buttons">
                <table style="text-align: center;" id="datatable" class="table table-striped table-bordered">
                    <thead class="text-bold-700">
                    <tr>
                        <td>{{__('Attribute')}}</td>
                        <td>{{__('Value')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{__('#ID')}}</td>
                        <td>{{$row->id}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Admin fullname')}}</td>
                        <td>{{$row->fullname}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Admin Email')}}</td>
                        <td>{{$row->email}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Status')}}</td>
                        <td>{{(($row->status=='1')?__('Active'):__('In-active'))}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Admin group')}}</td>
                        <td>{!! link_to_route('admin.admin-groups.show',$row->adminGroup->name,$row->adminGroup->id) !!}</td>
                    </tr>
                    </tbody>
                </table>
            </x-admin-card>
        </section>
        <!--/ Javascript sourced data -->

    </div>
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{app_asset('admin/css/jquery.dataTables.min.css')}}">
@endpush

@push('scripts')
    <script src="{{app_asset('admin/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{app_asset('dashboard/js/patient.js')}}" type="text/javascript"></script>
    <script>

        function editRecord(shiftId, from, to, office) {
            $('#form-patient-file').css('display', 'block');
            $('#patient_file_id').val(shiftId);
            $('#patient-files-input-patient-file').val(from);
            $('#patient-files-input-name input').val(to);
            $('#patient-files-input-file-type select').val(office);
        }

        $('#create-patient-file').on('click', function () {
            $(this).prop('disabled', true);
            $('#form-patient-file').css('display', 'block');
            $("#submit-form-patient-file").trigger('reset');
            $("#form-patient-file form small").empty();
        })

        $('#close-form-patient-file').on('click', function () {
            $('#form-patient-file').css('display', 'none');
            $("#submit-form-patient-file").trigger('reset');
            $('#create-patient-file').prop('disabled', false);
            $("#form-patient-file form small").empty();
        })

    </script>
@endpush
