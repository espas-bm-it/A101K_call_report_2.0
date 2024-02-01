<?php

namespace App\DataTables;

use App\Models\XmlData;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

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
        ->editColumn('DialledNumber', function ($data) {
            $phoneNumber = $data->DialledNumber;

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
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(2)
            ->selectStyleSingle()
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
            'SubscriberName',
            'DialledNumber',
            'Date',
            'Time',
            'RingingDuration',
            'CallDuration',
            'CallStatus',
            'CommunicationType',
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
