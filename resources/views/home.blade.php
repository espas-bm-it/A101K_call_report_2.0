@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="col col-9">
            </div>
            <div class="row">
                <div class="col col-3">
                    <!--DATE RANGE FILTER -->
                    <div id="daterange" class="float-end"
                        style="background: #ffff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; text-align:center">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span>
                        <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                <div class="col col-3">
                    <!--        Div for the select customer column       -->
                    <div id="selectCustomer-container"></div>       
                </div>
                <div class="col col-3">
                    <!--        Div for the select status column     -->
                    <div id="selectStatus-container" ></div>
                </div>
                <div class="col col-3"> 
                    <!--        Div for the reset button     -->
                    <div id="reset-btn" class="float-end"
                        style="background: #E7BDBB; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; text-align:center">
                        Zurücksetzen
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col col-3">
                    <!--        Div for the chart button     -->
                    <div id="barChart-btn" class="float-end"
                        style="background: #E7BDBB; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; text-align:center">
                        chart
                    </div>
                </div>
                <div class="col col-3">
                    <!--        Div for the chart button     -->
                    <div id="ajaxSee" class="float-end"
                        style="background: #E7BDBB; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; text-align:center">
                        ajax
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!! $dataTable->table(['class' => 'table table-bordered', 'id' => 'daterange_table']) !!}
            </div>
        </div>
    </div>
    <div>
        <canvas id="myChart"></canvas>
    </div>


@endsection


@push('scripts')
    <!-- DATE RANGE PICKER CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- DATE RANGE PICKER JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>

    
    {!! $dataTable->scripts() !!}
@endpush
