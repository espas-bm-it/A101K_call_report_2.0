<?php

namespace App\Http\Controllers;

use App\Models\XmlData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\View;
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


    public function updateXmlData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $updatedXmlDatas = XmlData::whereBetween('Date', [$startDate, $endDate])
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->get();

        return View::make('partials.xml_data_table', ['XmlDatas' => $updatedXmlDatas]);
    }

    private function calculateDateRange()
    {
        $latestCall = XmlData::latest('Date')->first();

        if ($latestCall) {
            $endDate = Carbon::parse($latestCall->Date);
            $startDate = $endDate->copy()->subDays(5)->startOfDay();
        } else {
            // Fallback, wenn keine Anrufe vorhanden sind
            $startDate = Carbon::now()->subDays(5)->startOfDay();
            $endDate = Carbon::now();
        }
    }

}
