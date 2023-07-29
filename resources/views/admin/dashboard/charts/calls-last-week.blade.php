<x-admin-card :title="__('Calls last week')" class="col-sm-6">
    <div data-id="calls_by_caller_type" data-route="{{ route('admin.statistics.calls_by_caller_type', null, false) }}">
        <form class="data-chart-form" id="calls_by_caller_type_form">
            {!! Form::hidden("type", "bar") !!}
            <x-admin-form-group-wrapper>
                {!! Form::text("date_from", now()->subDays(30)->toDateString(), ['class' => 'form-control datepicker']) !!}
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
