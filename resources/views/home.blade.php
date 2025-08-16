@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @can('clocking')
                <x-clockin />
            @endcan
            
            @can('can export all attendance')
                <x-export-all-attendance />
            @endcan
        </div>

    </div>
@endsection
