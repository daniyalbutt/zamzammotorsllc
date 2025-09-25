@extends('layouts.app')

@section('content')
    <div class="row">
        @role('hr')
            <x-hr-dashboard :data="$data" />
        @endrole
        @role('agent')
            <x-agent-dashboard :data="$data" />
        @endrole
        @role('customer')
            <x-customer-dashboard :data="$data" />
        @endrole
        @role('sales manager')
            <x-manager-dashboard :data="$data" />
        @endrole

        @can('clocking')
            <x-clockin />
        @endcan

        @can('can export all attendance')
     
            <x-export-all-attendance />
        @endcan


    </div>
@endsection
