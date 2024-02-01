@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                {!! $dataTable->table(['class' => 'table table-bordered']) !!}
            </div>
        </div>
    @endsection

    @push('scripts')
        {!! $dataTable->scripts() !!}
    @endpush
