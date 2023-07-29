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
                                        <td>{{__('qr')}}</td>
                                        <td>{{$row->qr}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('send_email')}}</td>
                                        <td>{{$row->send_email}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('send_message')}}</td>
                                        <td>{{$row->send_message}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('scan_qr')}}</td>
                                        <td>{{$row->scan_qr}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('email_model_id')}}</td>
                                        <td>{{$row->email_model_id}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('contact_id')}}</td>
                                        <td>{{$row->contact_id}}</td>
                                    </tr>
                    </tbody>
                </table>
            </x-admin-card>
        </section>
    </div>
@endsection
