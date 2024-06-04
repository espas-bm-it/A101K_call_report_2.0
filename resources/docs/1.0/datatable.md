# Datatable

---

- [explanation](#section-1)
- [generation](#section-2)
- [HTML-Builder](#section-3)

<a name="section-1"></a>
## Explanation

Yajra Datatables was used to manage the datatable. Most of the configuration options were selected using the HTML-Builder provided by Yajra Datatables.


<a name="section-2"></a>
## Generation

The datatable is generated in the view home using this method from yajra:

    <div class="card-body table-responsive">
        {!! $dataTable->table(['class' => 'table table-bordered  table-responsive', 'id' => 'daterange_table']) !!}
    </div>

<a name="section-3"></a>
## HTML-Builder

The HTML-Builder is located in app\DataTables\XmlDataDataTable.php this is where all configuration options were chosen.

```php
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
                    ['orderable' => true, 'targets' => [2]], 
                ],
                'language' => [
                    'url' => asset('lang/DE_CH.json') 
                ],
                'retrieve' => true, 
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
```