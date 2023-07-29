<script src="{{ app_asset('vue/vue'.(app()->environment('local') ? '':'.min').'.js') }}" type="text/javascript"></script>
<script src="{{ app_asset('vue/axios.min.js') }}" type="text/javascript"></script>
<script src="{{ app_asset('admin/plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ app_asset('admin/plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
<script src="{{ app_asset('admin/plugins/datepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
@if(app()->getLocale() == 'ar')
    <script src="{{ app_asset('admin/plugins/datepicker/js/locales/bootstrap-datetimepicker.ar.js') }}" type="text/javascript"></script>
@endif
<script src="{{ app_asset('admin/js/custom.js') }}" type="text/javascript"></script>
