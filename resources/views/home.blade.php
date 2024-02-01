@extends('layouts.app')



@section('content')
             {!! $dataTable->table(['class' => 'table table-bordered']) !!}
@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush
