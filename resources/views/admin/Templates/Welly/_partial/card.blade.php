<div {{ $attributes }} {{ $attributes->merge(['class'=>'row']) }}>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{$title}}</h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    @if(is_array($buttons))
                        @if(count($buttons))
                            @foreach($buttons as $button)
                            <li>
                                <a href="{{$button['link']}}"
                                   @if(array_key_exists('data-toggle', $button))
                                   data-toggle="{{$button['data-toggle'] ?? 'modal'}}"
                                   @endif
                                   @if(array_key_exists('data-target', $button))
                                   data-target="{{$button['data-target'] ?? '#filter-modal'}}"
                                   @endif
                                >
                                    @if($button['class'])
                                    <i class="{{$button['class']}}"></i>
                                    @endif
                                    {{$button['name']??''}}
                                </a>
                            </li>
                            @endforeach
                        @endif
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload" data-url="javascript:void(0);"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="card-body collapse in">
            {!! $slot !!}
        </div>
    </div>
</div>
