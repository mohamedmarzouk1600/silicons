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
                                        <td>{{__('name')}}</td>
                                        <td>{{$row->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('description')}}</td>
                                        <td>{{$row->description}}</td>
                                    </tr>
                    </tbody>
                </table>
            </x-admin-card>
        </section>
    </div>
@endsection
