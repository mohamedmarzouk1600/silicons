<div {{ $attributes }} {{ $attributes->class(['row','formCard']) }}>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <h2>{{$title}}</h2>
                @if(is_array($buttons))
                    @if(count($buttons))
                    <ul class="header-dropdown">
                        <li>
                            <a href="#" onclick="return false;" class="dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right" style="">
                                @foreach($buttons as $button)
                                <li>
                                    <a href="{{$button['link']}}"
                                       @if(array_key_exists('data-toggle', $button))
                                       data-toggle="{{$button['data-toggle'] ?? 'modal'}}"
                                       @endif
                                       @if(array_key_exists('data-target', $button))
                                       data-target="{{$button['data-target'] ?? '#filter-modal'}}" data-bs-toggle="modal" data-bs-target="{{$button['data-target'] ?? '#filter-modal'}}"
                                       @endif
                                    >
                                        <i class="{{$button['class']}}"></i>
                                        {{$button['name']??''}}

                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    @endif
                @endif
            </div>
            <div class="body wrapper">
                {!! $slot !!}
            </div>
        </div>
    </div>
</div>
