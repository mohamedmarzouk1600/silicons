@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing">
            <x-admin-card :title="$title" :buttons="$buttons">
                <table style="text-align: center;" class="table table-striped table-bordered">
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
                                        <td>{{__('email')}}</td>
                                        <td>{{$row->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('phone')}}</td>
                                        <td>{{$row->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('type')}}</td>
                                        <td>{{$row->type}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('event_id')}}</td>
                                        <td>{{$row->event_id}}</td>
                                    </tr>
                    </tbody>
                </table>
            </x-admin-card>
        </section>
    </div>
@endsection
