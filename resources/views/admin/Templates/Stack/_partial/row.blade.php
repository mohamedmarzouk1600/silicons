<div {{ $attributes->merge(['class'=>'']) }} {{$attributes}}>
        {!! $slot !!}
    <div class="clearfix"></div>
</div>
