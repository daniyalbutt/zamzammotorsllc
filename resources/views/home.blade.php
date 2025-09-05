@extends('layouts.app')

@section('content')
    <div class="row">
        @role('hr')
            <x-hr-dashboard :data="$data" />
        @endrole

        @can('clocking')
            <x-clockin />
        @endcan

        @can('can export all attendance')
     
            <x-export-all-attendance />
        @endcan


    </div>
@endsection
