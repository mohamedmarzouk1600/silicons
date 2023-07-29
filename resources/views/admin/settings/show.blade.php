@extends('admin.layout')

@section('content')
<x-admin-card :title="$title">
    <table class="table table-striped">
        <tbody>
        <tr class="text-primary">
            <td>{{__('Setting Name')}}</td>
            <td>{{$row->name}}</td>
        </tr>
        @foreach($row->getAttributes() as $key=>$val)
            <tr>
                <td>{{$key}}</td>
                <td>{{$val}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-admin-card>
@endsection
