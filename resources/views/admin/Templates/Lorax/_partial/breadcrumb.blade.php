<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb breadcrumb-style ">
            <li class="breadcrumb-item">
                <h4 class="page-title">{{$title}}</h4>
            </li>
            <li class="breadcrumb-item bcrumb-1">
                <a href="{{route('admin.dashboard')}}">
                    <i class="fas fa-home"></i> {{__('Home')}}</a>
            </li>
            @if(isset($items) && count($items))
                @foreach($items as $index=>$item)
                    @php($index++)
                <li class="breadcrumb-item {{(count($items) == $index ? 'active' : 'bcrumb-'.$index)}}">
                    <a href="{{$item->link}}">{{$item->name}}</a>
                </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
