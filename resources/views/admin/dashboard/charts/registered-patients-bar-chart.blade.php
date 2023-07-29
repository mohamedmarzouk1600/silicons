<x-admin-card :title="__('Registered patients')" class="col-sm-6">
    <div data-id="registered_patients_per_day" data-route="{{ route('admin.statistics.registered_patients_per_day', null, false) }}">
        <form class="data-chart-form" id="registered_patients_per_day_form">
            {!! Form::hidden("type", "bar") !!}
            {!! Form::hidden("label", __('Registered patients')) !!}
            <x-admin-form-group-wrapper>
                {!! Form::text("date_from", now()->subDays(10)->toDateString(), ['class' => 'form-control datepicker']) !!}
            </x-admin-form-group-wrapper>
            <x-admin-form-group-wrapper>
                {!! Form::text("date_to", now()->addDay(1)->toDateString(), ['class' => 'form-control datepicker']) !!}
            </x-admin-form-group-wrapper>
            <div class="card-block card-dashboard container-submit-input">
                <button class="btn btn-success pull-right refresh-chart">
                    <span id="change-gender-text">
                        {{ __('refresh') }}
                    </span>
                    <span id="change-gender-spinner" style="display: none">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-admin-card>
