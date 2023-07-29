@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Value')}}</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td>{{__('ID')}}</td>
                        <td>{{$row->id}}</td>
                    </tr>


                    <tr>
                        <td>{{__('Log Name')}}</td>
                        <td>{{$row->log_name}}</td>
                    </tr>


                    <tr>
                        <td>{{__('Status')}}</td>
                        <td>{{$row->description}}</td>
                    </tr>

                    <tr>
                        <td>{{__('Model')}}</td>
                        <td>{{$row->subject_type}} ({{$row->subject_id}})</td>
                    </tr>

                    <tr>
                        <td>{{__('User')}}</td>
                        <td>{{$row->causer_type}} ({{$row->causer_id}})</td>
                    </tr>

                    <tr>
                        <td>{{__('Device')}}</td>
                        <td>
                            {{$row->agent->device()}}
                            @if($row->agent->isDesktop())
                                {{__('Desktop')}}
                            @elseif($row->agent->isMobile())
                                {{__('Mobile')}}
                            @elseif($row->agent->isTablet())
                                {{__('Tablet')}}
                            @else
                                --
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <td>{{__('Platform')}}</td>
                        <td>{{$row->agent->platform()}} {{$row->agent->version($row->agent->platform())}}</td>
                    </tr>


                    <tr>
                        <td>{{__('IP')}}</td>
                        <td>{{$row->ip}}</td>
                    </tr>


                    <tr>
                        <td>{{__('Browser')}}</td>
                        <td>{{$row->agent->browser()}}</td>
                    </tr>

                    <tr>
                        <td>{{__('Languages')}}</td>
                        <td>{{implode(',',$row->agent->languages())}}</td>
                    </tr>








                    @if(isset($row->location))
                        <tr>
                            <td>{{__('Country')}}</td>
                            <td>{{$row->location->country}} ({{$row->location->countryCode}})</td>
                        </tr>
                        <tr>
                            <td>{{__('city')}}</td>
                            <td>{{$row->location->city}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Region Name')}}</td>
                            <td>{{$row->location->regionName}}</td>
                        </tr>
                        <tr>
                            <td>{{__('ISP')}}</td>
                            <td>{{$row->location->isp}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Latitude')}}</td>
                            <td>{{$row->location->lat}}</td>
                        </tr>
                        <tr>
                            <td>{{__('Longitude')}}</td>
                            <td>{{$row->location->lon}}</td>
                        </tr>
                    @endif

                    <tr>
                        <td>{{__('URL')}}</td>
                        <td>{{$row->method}} <a href="{{$row->url}}" target="_blank">{{$row->url}}</a> </td>
                    </tr>

                    <tr>
                        <td>{{__('Created At')}}</td>
                        <td>
                            @if($row->created_at == null)
                                --
                            @else
                                {{$row->created_at->diffForHumans()}}
                            @endif
                        </td>
                    </tr>

                    </tbody>
                </table>

                @if($row->properties->has('attributes'))

                    <hr>

                    <h3 style="text-align: center;">{{__('Data')}}</h3>

                    <table class="table" border="1">
                        <thead>
                        <tr>
                            <th>Key</th>
                            <th>{{__('Attributes')}}</th>
                            @if(isset($row->properties['old']))
                                <th>{{__('Old')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                            @php
                                $keys = array_keys($row->properties['attributes']);
                            @endphp

                            @foreach($keys as $value)
                                <tr>
                                    <td>{{$value}}</td>
                                    <td {!!((isset($row->properties['old']) && $row->properties['attributes'][$value] != $row->properties['old'][$value])?' class="text-danger"':null)!!}>{{$row->properties['attributes'][$value]}}</td>
                                    @if(isset($row->properties['old']))
                                        <td>{{$row->properties['old'][$value]}}</td>
                                    @endif
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @endif

                @if($row->event == 'deleted' && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($row->subject_type)))
                    <div class="actions">
                        <a href="{{ route('admin.model-deleted.restore', ['model' => $row->subject_type, 'id' => $row->subject_id]) }}" class="restore-action btn btn-info">{{ __('Restore') }}</a>
                    </div>
                @endif
            </x-admin-card>

        </section>
        <!--/ Javascript sourced data -->
    </div>
@endsection
