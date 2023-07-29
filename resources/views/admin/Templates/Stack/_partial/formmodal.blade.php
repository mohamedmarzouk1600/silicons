<div {{ $attributes }} {{ $attributes->merge(['class' => 'modal fade model-form-show-patient']) }} tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade mt-2 mb-0 mr-2 ml-2" role="alert">
                <strong>{{ __('Error') }} !</strong>
                <span id="model-form-error-messages"></span>
            </div>
            {!! Form::open(['method' => 'get']) !!}
                <div class="modal-body">
                    <x-admin-row>
                        {!! $slot !!}
                    </x-admin-row>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary" id="submitModelForm">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>{{ __('Save') }}</span>
                    </button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            let model = '.model-form-show-patient';
            $(model).on('hidden.bs.modal', function () {
                $('.patient-show-blade .alert-danger').css({'display' : 'none', 'opacity' : 0});
                $(this).children('form').trigger('reset');

                $('#{{ $attributes['id'] }} #submitModelForm i').css('display', 'none');
                $('#{{ $attributes['id'] }} #submitModelForm span').css('display', 'block');

                /**
                 * These selectors in patient-readings.js component
                 */
                $('.content-selected-reading-types').css('display', 'none').empty();
                $('.select-patient-reading').val(0);
            })

            $(model).on('shown.bs.modal', function () {
                $('#{{ $attributes['id'] }} #submitModelForm i').css('display', 'none');
                $('#{{ $attributes['id'] }} #submitModelForm span').css('display', 'block');

                $(this).children('form').trigger('reset');

                /**
                 * These selectors in patient-readings.js component
                 */
                $('.content-selected-reading-types').css('display', 'block');
            })
        });
    </script>
@endpush
