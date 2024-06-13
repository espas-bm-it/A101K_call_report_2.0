
@extends('layouts.app') @section('content') 
<div class="card my-4">
  <div class="card-header">
    <div class="col col-12">
      <div class="row">
        <div class="btn-group mt-3" role="group" aria-label="Basic example">
          <!--DATE RANGE FILTER -->
          <button type="button" id="daterange" class="btn btn-outline-primary rounded-2 m-2 p-0"></button>
          <!--SELECT CUSTOMER -->
          <button type="button" id="selectCustomer-container" class="btn m-2 p-0"></button>
          <!-- SELECT COMMUNICATION TYPE -->
          <button type="button" id="selectCommunicationType-container" class="btn m-2 p-0"></button>
          <!-- RESET BUTTON -->
          <button type="button" id="reset-btn" class="float-end btn btn-outline-warning link-primary rounded-2 m-2 p-0">Zurücksetzen</button>
        </div>
      </div>
      <div class="row">
        <div class="col col-12">
          <div class="text-center">
            <div id="graphicalDisplay" class="btn btn-info mt-3">
              <i class="fa-solid fa-chart-simple"></i> Grafische Darstellung

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
        <div class="card-body table-responsive p-4"> {!! $dataTable->table(['class' => 'table table-bordered table-responsive mt-4 pt-8', 'id' => 'daterange_table']) !!} </div>
      </div>
    </div>
    <div class="container mt-3">
      <div class="row">
        <div class="col col-4">
          <div class="card p-2">
            <div style="overflow: hidden">
              <canvas id="myBarChart" style="display: none;"></canvas>
            </div>
          </div>
        </div>
        <div class="col col-4">
          <div class="card p-2">
            <div style="overflow: hidden">
              <p id="serviceRating" class="text-primary fs-5 fw-bolder text-center" style="display: none;"></p>
            </div>
            <div style="overflow: hidden">
              <canvas id="myPieChart" style="display: none;"></canvas>
            </div>
          </div>
        </div>
        <div class="col col-4">
          <div class="card p-2">
            <div style="overflow: hidden">
              <canvas id="myTimeHistoryChart" style="display: none;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row px-4">
        <div class="col col-6">
          <div style="overflow: hidden">
            <canvas id="outgoingChart" style="display: none;"></canvas>
          </div>
        </div>
        <div class="col col-6">
          <div style="overflow: hidden">
            <canvas id="outgoingChartPie" style="display: none;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> @endsection @push('scripts')
<!-- DATE RANGE PICKER CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- DATE RANGE PICKER JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script> {!! $dataTable->scripts() !!} @endpush