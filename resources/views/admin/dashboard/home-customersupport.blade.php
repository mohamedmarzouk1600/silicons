@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing" class="charts-home-blade">
            <x-admin-row>
                @include('admin.dashboard.charts.states')
                @include('admin.dashboard.charts.registered-patients-bar-chart')
                @include('admin.dashboard.charts.calls')
                @include('admin.dashboard.charts.patients-missed-calls')
            </x-admin-row>
        </section>
    </div>
@endsection

<!-- If You Have Charts Includes Above : You Should Remove Below Comment Of Include To Run Chart Js Files -->
@include('admin.dashboard._partial.chart-js')
<!-- -->
