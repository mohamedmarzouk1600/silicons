<div class="model-btn-content">
    <button type="button" data-toggle="modal" {{ $attributes }} {{ $attributes->merge(['class' => 'btn btn-primary btn-open-model']) }}>
        {!! $slot !!}
    </button>
</div>
