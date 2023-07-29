@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing" class="charts-home-blade">
            @include('admin.dashboard.charts.states')
            <x-admin-row>

            </x-admin-row>
        </section>
    </div>
@endsection

@include('admin.dashboard._partial.chart-js')
