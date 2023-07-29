@extends('admin.layout')

@section('content')
    <div class="content-body">
        <!-- Server-side processing -->
        <section id="server-processing" class="charts-home-blade">
            <x-admin-card :title="$title">
                <div class="card-block card-dashboard"></div>
            </x-admin-card>
        </section>
    </div>
@endsection
