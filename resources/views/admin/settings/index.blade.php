@extends('admin.layout')

@section('content')

    <div class="card-content">
        <div class="card-body">
            <ul class="nav nav-tabs nav-top-border no-hover-bg" role="tablist">
                @foreach(array_merge(['none'],\MaxDev\Models\Setting::where('groups','!=','none')->groupBy('groups')->get()->pluck('groups')->toArray()) as $group)
                <li class="nav-item">
                    <a class="nav-link {{$group == request()->groups ? 'active' : '' }}" id="basic-tab1" href="{{ url('administrators/settings?groups=').$group }}" aria-controls="basic" role="tab" aria-selected="true">
                        <i class="fa fa-align-justify"></i>
                        {{ __($group) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <x-admin-card :title="$title" :buttons="$buttons">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>{{ __('Settings name') }}</th>
                @foreach(config('app.locales') as $locale)
                    <th>{{$locale}}</th>
                @endforeach
                <th>{{__('Edit')}}</th>
                <th>{{__('Delete')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    @foreach(config('app.locales') as $locale)
                        <td>{{$row->getTranslation('value',$locale)}}</td>
                    @endforeach
                    <td><a href='{{route('admin.settings.edit',$row->id)}}'>{{__('Edit')}}</a></td>
                    <td><a onclick="deleteRecord('{{route('admin.settings.destroy',$row->id)}}',this);" href="javascript:void(0);">{{__('Delete')}}</a>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        <nav class="Page navigation" style="background-color: transparent !important;">
            {{ $rows->links() }}
        </nav>
    </x-admin-card>
@endsection
