@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing" class="charts-home-blade">
            <x-admin-row>
                <!-- Put Your Includes Here -->
            </x-admin-row>
        </section>
    </div>
@endsection

<!-- If You Have Includes Above : You Should Remove Below Comment To Run Chart Js Files -->
{{-- @include('admin.dashboard._partial.chart-js') --}}
<!-- -->
