<?php

namespace App\Http\Controllers;


use Kyslik\ColumnSortable\Sortable;

use App\DataTables\XmlDataDataTable;

class HomeController extends Controller
{
    use Sortable;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(XmlDataDataTable $dataTable)
    {

        return $dataTable->render('home');
    }

    public function datatables(XmlDataDataTable $dataTable)
    {
        
        return $dataTable->ajax();

    }


}
