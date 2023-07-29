@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing" class="charts-home-blade">
            @include('admin.dashboard.charts.states')
            <x-admin-row>
                @include('admin.dashboard.charts.calls-last-week')
                @include('admin.dashboard.charts.all-calls-by-doctors')
                @include('admin.dashboard.charts.call-per-recommendations')
            </x-admin-row>
        </section>
    </div>
@endsection

@include('admin.dashboard._partial.chart-js')
@include('admin.dashboard._partial.calls-by-doctors-js')
