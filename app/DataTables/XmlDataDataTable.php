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
                    return Carbon::parse($model->Date)->isoFormat('DD.MM.YYYY');
                } else {
                    // Log if the 'Date' property is missing or null
                    \Illuminate\Support\Facades\Log::info("Date property is missing or null");
                    return null; // Or any fallback value you prefer
                }
            })

            ->editColumn('SubscriberName', function ($model) {
                // Display "Nicht registriert" if SubscriberName is null or empty
                return $model->SubscriberName ? $model->SubscriberName : 'Nicht registriert';
            })

            ->filterColumn('formatted_date', function ($query, $keyword) {
                $dates = explode('|', $keyword);

                // Check if the input consists of two date values
                if (count($dates) == 2) {
                    $startDate = Carbon::createFromFormat('d-m-Y', $dates[0])->startOfDay();
                    $endDate = Carbon::createFromFormat('d-m-Y', $dates[1])->endOfDay();

                    // Only apply the date range filter if both dates are valid
                    if ($startDate && $endDate) {
                        $query->whereBetween('Date', [$startDate, $endDate]);
                    }
                }
            })

            ->editColumn('formatted_date', function ($model) {
                return Carbon::parse($model->Date)->isoFormat('DD.MM.YYYY');
            })

            ->rawColumns(['formatted_date'])
            ->orderColumn('formatted_date', function ($query, $order) {
                // Sort the query based on the 'Date' column
                $query->orderBy('Date', $order)->orderBy('Time',$order);
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
        // Getting unique SubscriberName values for customer selection
        $uniqueSubscriberNames = XmlData::pluck('SubscriberName')->unique()->values()->toArray();

        // Sort the array alphabetically
        sort($uniqueSubscriberNames);

        $subscriberNameOptions = ''; // Default option
        foreach ($uniqueSubscriberNames as $subscriberName) {
            $subscriberNameOptions .= '<option value="' . $subscriberName . '">' . $subscriberName . '</option>';
        }

        // Update select for column 0 (SubscriberName)
        $subscriberNameOptions = '<option value="" style="text-align:center;" >Kunde</option>' . $subscriberNameOptions;

        // Getting unique CallStatus values for column 6
        $uniqueCallStatuses = XmlData::pluck('CallStatus')->unique()->values()->toArray();

        // Sort the array alphabetically
        sort($uniqueCallStatuses);

        $callStatusOptions = ''; // Default option
        foreach ($uniqueCallStatuses as $callStatus) {
            $callStatusOptions .= '<option value="' . $callStatus . '">' . $callStatus . '</option>';
        }


        // Add "Filter auflösen" option as the first option
        $callStatusOptions = '<option value="" style="text-align:center;">Anrufstatus</option>' . $callStatusOptions;

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2)
            ->responsive(true)
            ->selectStyleSingle()
            ->parameters([
                'columnDefs' => [
                    ['orderable' => true, 'targets' => [2]], // Specify the index of your custom column

                   /* ['width' => '10%', 'targets' => [0]],  
                    ['width' => '10%', 'targets' => [1]],  
                    ['width' => '10%', 'targets' => [2]],  
                    ['width' => '10%', 'targets' => [3]], 
                    ['width' => '10%', 'targets' => [4]], 
                    ['width' => '10%', 'targets' => [5]], 
                    ['width' => '10%', 'targets' => [6]], 
                    ['width' => '10%', 'targets' => [7]], */
                ],
                'language' => [
                    'url' => asset('lang/DE_CH.json') // URL to the language file
                ],
                'retrieve' => true, // Add the retrieve: true option
                'initComplete' => 'function(settings, json) {
                var api = this.api();
            

                // Update select filters for column 0 (SubscriberName)
                $("#selectCustomer-container").html(\'<select id="selectColumn0" class="form-select" >' . $subscriberNameOptions . '</select>\');
                $("#selectCustomer-container select").on("change", function() {
                    var selectedValue = $(this).val();
                    api.column(0).search(selectedValue).draw();
                });

                // Update select filters for column 6 (CallStatus)
                $("#selectStatus-container").html(\'<select id="selectColumn6" class="form-select" >' . $callStatusOptions . '</select>\');
                $("#selectStatus-container select").on("change", function() {
                    var selectedValue = $(this).val();
                    api.column(6).search(selectedValue).draw();
                });
            
                // Update select filters for column 7 (CommunicationType)
                var uniqueCommunicationTypes = api.column(7).data().unique().sort();
                var communicationTypeOptions = \'<option value="" style="text-align:center;">Anruftyp</option>\';
            
                $.each(uniqueCommunicationTypes, function (index, value) {
                    communicationTypeOptions += \'<option value="\' + value + \'">\' + value + \'</option>\';
                });
            
                $("#selectCommunicationType-container").html(\'<select id="selectColumn7" class="form-select" >\' + communicationTypeOptions + \'</select>\');
                $("#selectCommunicationType-container select").on("change", function() {
                    var selectedValue = $(this).val();
                    api.column(7).search(selectedValue).draw();
                });
            
                // Set selected options based on current filters
                var currentFilter0 = api.column(0).search();
                $("#selectCustomer-container select").val(currentFilter0);
            
                var currentFilter6 = api.column(6).search();
                $("#selectStatus-container select").val(currentFilter6);
            
                var currentFilter7 = api.column(7).search();
                $("#selectCommunicationType-container select").val(currentFilter7);
            
                // Initialize the Date Range Picker here
                var dateRangeInput = $("#daterange");
                var dataTable = $("#daterange_table").DataTable(); // Select the DataTable by its ID

                
            
                // Set the default message
                dateRangeInput.html("Datumsbereich");
            
                dateRangeInput.daterangepicker({
                    opens: "left",
                    ranges: {
                        "Heute": [moment(), moment()],
                        "Gestern": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                        "Letzten 7 Tage": [moment().subtract(6, "days"), moment()],
                        "Diesen Monat": [moment().startOf("month"), moment().endOf("month")],
                        "Letzten Monat": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")],
                        "Letzten 365 Tage": [moment().subtract(365, "days"), moment()]
                    },
                    alwaysShowCalendars: true,
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

                    console.log(startDate)
                    console.log(endDate)
                }
                );
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
            'SubscriberName' => ['title' => 'Kunde'],
            'DialledNumber' => ['title' => 'Telefonnummer'],
            'formatted_date' => ['title' => 'Datum'],
            'Time' => ['title' => 'Uhrzeit'],
            'RingingDuration' => ['title' => 'Klingeldauer'],
            'CallDuration' => ['title' => 'Anrufdauer'],
            'CallStatus' => ['title' => 'Anrufstatus'],
            'CommunicationType' => ['title' => 'Anruftyp'],
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
