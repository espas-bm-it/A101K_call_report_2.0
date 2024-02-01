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
            ->eloquent($query);
            
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
