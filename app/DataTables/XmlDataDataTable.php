<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\XmlData;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class XmlDataDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
{
    return datatables()
        ->eloquent($query)
        ->addColumn('formatted_date', function ($model) {
            // Check if the 'Date' property exists and is not null
            if (isset($model->Date)) {
                // Format the 'Date' property as 'formatted_date'
                $formattedDate = Carbon::parse($model->Date)->isoFormat('DD.MM.YYYY');
                
            } else {
                // Log if the 'Date' property is missing or null
                \Illuminate\Support\Facades\Log::info("Date property is missing or null");
                return null; // Or any fallback value you prefer
            }
        })
        ->filterColumn('formatted_date', function ($query, $keyword) {
            $dates = explode('|', $keyword);
            $startDate = Carbon::createFromFormat('d-m-Y', $dates[0])->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $dates[1])->endOfDay();
            $query->whereBetween('Date', [$startDate, $endDate]);
        })
        ->editColumn('formatted_date', function ($model) {
            return Carbon::parse($model->Date)->isoFormat('DD.MM.YYYY');
            
        })
        ->rawColumns(['formatted_date'])
        ->orderColumn('formatted_date', function ($query, $order) {
            // Sort the query based on the 'Date' column
            $query->orderBy('Date', $order);
            
        })
        ->editColumn('DialledNumber', function ($model) {
            $phoneNumber = $model->DialledNumber;

            $countryCodes = [
                '41' => 'Schweiz',
                '44' => 'Großbritannien',
                // Weitere Ländercodes hier hinzufügen...
            ];

            $countryCode = substr($phoneNumber, 0, 2);

            if (isset($countryCodes[$countryCode])) {
                $formattedNumber = '+' . $countryCode . ' ';

                switch ($countryCode) {
                    case '41': // Schweiz
                        $formattedNumber .= substr($phoneNumber, 2, 2) . ' ' . substr($phoneNumber, 4, 3) . ' ' . substr($phoneNumber, 7, 2) . ' ' . substr($phoneNumber, 9, 2);
                        break;
                    case '44': // Großbritannien
                        $formattedNumber .= substr($phoneNumber, 2, 4) . ' ' . substr($phoneNumber, 6, 4) . ' ' . substr($phoneNumber, 10, 2) . ' ' . substr($phoneNumber, 12, 2);
                        break;
                    // Weitere Ländercodes hier hinzufügen...
                    default:
                        $formattedNumber .= substr($phoneNumber, 2);
                        break;
                }
            } else {
                if (strlen($phoneNumber) == 10) {
                    $formattedNumber = '+41 ' . substr($phoneNumber, 1, 2) . ' ' . substr($phoneNumber, 3, 3) . ' ' . substr($phoneNumber, 6, 2) . ' ' . substr($phoneNumber, 8, 2);
                } elseif (strlen($phoneNumber) == 11) {
                    $formattedNumber = '+' . substr($phoneNumber, 0, 2) . ' ' . substr($phoneNumber, 2, 2) . ' ' . substr($phoneNumber, 4, 3) . ' ' . substr($phoneNumber, 7, 2) . ' ' . substr($phoneNumber, 9, 2);
                } else {
                    $formattedNumber = $phoneNumber;
                }
            }

            return $formattedNumber;
        });
}

    /**
     * Get the query source of dataTable.
     */
    public function query(XmlData $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
{
    // Getting unique SubscriberName values for column 0
    $uniqueSubscriberNames = XmlData::pluck('SubscriberName')->unique()->values()->toArray();
    $subscriberNameOptions = '<option value="" selected style="font-weight: bold;">Filter auflösen</option>'; // Default option
    foreach ($uniqueSubscriberNames as $subscriberName) {
        $subscriberNameOptions .= '<option value="' . $subscriberName . '">' . $subscriberName . '</option>';
    }

    // Update footer for column 0 (SubscriberName)
    $subscriberNameOptions = '<option value="">Filter auswählen</option>' . $subscriberNameOptions;

    // Getting unique CallStatus values for column 6
    $uniqueCallStatuses = XmlData::pluck('CallStatus')->unique()->values()->toArray();
    $callStatusOptions = '<option value="" selected style="font-weight: bold;">Filter auflösen</option>'; // Default option
    foreach ($uniqueCallStatuses as $callStatus) {
        $callStatusOptions .= '<option value="' . $callStatus . '">' . $callStatus . '</option>';
    }

    // Add "Filter auflösen" option as the first option
    $callStatusOptions = '<option value="">Filter auswählen</option>' . $callStatusOptions;

    

    return $this->builder()
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->orderBy(2)
        ->selectStyleSingle()
        ->parameters([
            'columnDefs' => [
                ['orderable' => true, 'targets' => [2]] // Specify the index of your custom column
            ],
            'drawCallback' => 'function() {
                $(".dataTables_filter").hide();
            }',
            'footerCallback' => "
                function (row, data, start, end, display) {
                    var api = this.api();

                    // Update footer for column 0 (SubscriberName)
                    api.column(0).footer().innerHTML = '<select id=\"selectColumn0\">" . $subscriberNameOptions . "</select>';
                    // Add onchange event handler for column 0
                    $('#selectColumn0').on('change', function () {
                        var selectedValue = $(this).val();
                        api.column(0).search(selectedValue).draw();
                    });

                    // Set selected option based on current filter for column 0
                    var currentFilter0 = api.column(0).search();
                    $('#selectColumn0').val(currentFilter0);

                    

                    // Update footer for column 6 (CallStatus)
                    api.column(6).footer().innerHTML = '<select id=\"selectColumn6\">" . $callStatusOptions . "</select>';
                    // Add onchange event handler for column 6
                    $('#selectColumn6').on('change', function () {
                        var selectedValue = $(this).val();
                        api.column(6).search(selectedValue).draw();
                    });

                    // Set selected option based on current filter for column 6
                    var currentFilter6 = api.column(6).search();
                    $('#selectColumn6').val(currentFilter6);
                }
            ",
            'initComplete' => 'function(settings, json) {
                $(document).ready(function() {
                    // Initialize the Date Range Picker here
                    var dateRangeInput = $("#daterange");
                    var dataTable = $("#daterange_table").DataTable(); // Select the DataTable by its ID
            
                    // Set the default message
                    dateRangeInput.html("Datumsbereich auswählen");
            
                    dateRangeInput.daterangepicker({
                        opens: "left",
                        locale: {
                            format: "DD-MM-YYYY"
                        }
                    }, function (start, end, label) {
                        // Callback function when date range is selected
                        var startDate = start.format("DD-MM-YYYY");
                        var endDate = end.format("DD-MM-YYYY");
            
                        // Update the selected date range in the div
                        dateRangeInput.html(startDate + "  |  " + endDate);
            
                        // Update the DataTable with the selected date range
                        dataTable.column(2).search(startDate + "|" + endDate, true, false).draw(); // Search and draw for the date range
                    });
                });
            }',
        ])
        ->buttons([
            Button::make('excel'),
            Button::make('csv'),
            Button::make('pdf'),
            Button::make('print'),
            Button::make('reset'),
            Button::make('reload')
        ]);
}


    /**
     * Get the dataTable columns definition.
     */
    protected function getColumns()
    {
        return [
            'SubscriberName'=> ['title' => 'Kund'],
            'DialledNumber'=> ['title' => 'Tel. Nummer'],
            'formatted_date'=> ['title' => 'Datum'],
            'Time'=> ['title' => 'Uhrzeit'],
            'RingingDuration'=> ['title' => 'Klingeldauer'],
            'CallDuration'=> ['title' => 'A. Dauer'],
            'CallStatus'=> ['title' => 'A. Status'],
            'CommunicationType'=> ['title' => 'A. Typ'],
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'XmlData_' . date('YmdHis');
    }
}