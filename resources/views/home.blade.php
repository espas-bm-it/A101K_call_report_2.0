@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="col col-9">
            </div>

            <div class="col col-3">

                <!--DATE RANGE FILTER -->
                <div id="daterange" class="float-end"
                    style="background: #ffff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; text-align:center">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span>
                    <i class="fa fa-caret-down"></i>
                </div>
                <!--        ------         -->
            </div>
            <div class="card-body">
                {!! $dataTable->table(['class' => 'table table-bordered', 'id' => 'daterange_table'], true) !!}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
