@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="col col-12">
                <div class="row">

                    <div class="col col-2">
                        <!--DATE RANGE FILTER -->
                        <div id="daterange" class="float-end"
                            style="background: #ffff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; text-align:center">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                    </div>


                    <div class="col col-2">
                        <!--        Div for the select customer column       -->
                        <div id="selectCustomer-container"></div>
                    </div>

                    <div class="col col-2">
                        <!--        Div for the select status column     -->
                        <div id="selectStatus-container"></div>
                    </div>

                    <div class="col col-2">
                        <!-- Div for the select communicationType column -->
                        <div id="selectCommunicationType-container"></div>
                    </div>

                    <div class="col col-2">
                        <!-- Div for the select communicationType column -->
                        <div id=""></div>
                    </div>

                    <div class="col col-2">
                        <!--        Div for the resset button     -->
                        <div id="reset-btn" class="float-end"
                            style="background: #E7BDBB; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; text-align:center">
                            Zurücksetzen
                        </div>

                    </div>
                    </div>
            <div class="row">
                
                <div class="col col-3">
                    <!--        Div for the graphics button     -->
                    <div id="graphicalDisplay" class="float-end"
                    style="background: #ffff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; text-align:center; margin: 5px;">
                        Graphische Darstellung
                    </div>
                </div>

                <!-- NEW COLUMN
                        <div class="row">
                            <div class="col col-2">
                            </div>

                        </div>  -->

            </div>

            <div class="card-body table-responsive">
                {!! $dataTable->table(['class' => 'table table-bordered  table-responsive', 'id' => 'daterange_table']) !!}
            </div>
        </div>
    </div>
    <div class="col col-12">
        <div class="row">
            <div class="col col-5">
                <div style="overflow: hidden">
                    <canvas id="myBarChart" style="display: none;"></canvas>
                </div>
            </div>
            <div class="col col-3">
                <div style="overflow: hidden">
                    <canvas id="myPieChart" style="display: none;"></canvas>
                </div>
            </div>
            <div class="col col-3 d-flex align-items-center justify-content-center">
                <div style="overflow: hidden" >
                    <p id="serviceRating" style="display: none; font-weight: bold; font-size: larger;"></p>
                </div>
            </div>
        </div>
        <div class="row"> 
            <div class="col col-5">
                <div style="overflow: hidden">
                    <canvas id="outgoingChart" style="display: none;"></canvas>
                </div>
            </div>
            <div class="col col-3">
                <div style="overflow: hidden">
                    <canvas id="outgoingChartPie" style="display: none;"></canvas>
                </div>
            </div>
        </div>
        <div class ="row">
            <div class="col col-9">
                <div style="overflow: hidden">
                    <canvas id="myTimeHistoryChart" style="display: none;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <!-- DATE RANGE PICKER CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- DATE RANGE PICKER JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>


    {!! $dataTable->scripts() !!}
@endpush
