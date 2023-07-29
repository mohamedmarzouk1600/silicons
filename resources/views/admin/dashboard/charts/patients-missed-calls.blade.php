@push('css')
    <link rel="stylesheet" type="text/css" href="{{app_asset('admin/css/datatable/dataTables.bootstrap4.min.css')}}">
@endpush

<x-admin-card :title="__('Patients missed calls')" class="col-sm-6 patients-missed-calls">
    <table style="text-align: center;width: 100%" id="datatable-patients-missed-calls" class="table table-striped table-bordered">
        <thead>
            <tr>
                @foreach($tableColumns as $key => $value)
                    <th>{{ $value }}</th>
                @endforeach
            </tr>
        </thead>
        <tfoot>
            <tr>
                @foreach($tableColumns as $key => $value)
                    <th>{{ $value }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
</x-admin-card>

@push('scripts')
    <script src="{{ app_asset('admin/js/datatable/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ app_asset('admin/js/datatable/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            generateDatatableCode(
                "table#datatable-patients-missed-calls",
                '{{ asset('admin/js/datatables/language/' . app()->getLocale() . '.json') }}',
                10,
                "{{ route('admin.statistics.patients_missed_calls') }}",
                '{{ getObjectsFromArray($cols) }}'
            )
        });
    </script>
@endpush
