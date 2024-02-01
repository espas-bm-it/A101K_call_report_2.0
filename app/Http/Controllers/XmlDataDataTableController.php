<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\XmlDataDataTable;

class XmlDataDataTableController extends Controller
{
    public function index(XmlDataDataTable $dataTable)
    {
        return $dataTable->render('home');
    }

    public function datatables(XmlDataDataTable $dataTable)
{
    return $dataTable->ajax();
}
}
