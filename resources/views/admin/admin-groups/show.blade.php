@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title" :buttons="$buttons">
                <table style="text-align: center;" id="datatable" class="table table-striped table-bordered">
                    <thead class="text-bold-700">
                    <tr>
                        <td>{{__('Attribute')}}</td>
                        <td>{{__('Value')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{__('#ID')}}</td>
                        <td>{{$row->id}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Group name')}}</td>
                        <td>{{$row->name}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Group home page')}}</td>
                        <td>
                            {{$row->home_url}}
                            <br>
                            {{$row->url_index}}
                        </td>
                    </tr>
                    <tr>
                        <td>{{__('Group status')}}</td>
                        <td>{{(($row->status=='1')?__('Active'):__('In-active'))}}</td>
                    </tr>
                    <tr>
                        <td>{{__('Group admins')}}</td>
                        <td>
                            <ul>
                                @foreach($row->admins as $admin)
                                    <li>
                                        {!! link_to_route('admin.admins.show',$admin->fullname,$admin->id) !!}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>{{__('Group permissions')}}</td>
                        <td>
                            <ul style="text-align: justify">
                                @foreach($row->permissions as $permission)
                                    <li>{{ $permission->route_name }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </x-admin-card>
        </section>
    </div>
@endsection
