<?php

namespace App\Http\Controllers;

use App\Models\XmlData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Seblhaire\DateRangePickerHelper\DateRangePickerHelper;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    use Sortable;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $sortableColumns = ['SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];

        list($startDate, $endDate) = $this->calculateDateRange();

        $XmlDatas = XmlData::sortable($sortableColumns)->select('SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType')
            ->whereNotIn('CommunicationType', ['BreakIn', 'FacilityRequest'])
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->paginate(10);

        $start = new Carbon('6 days ago');
        $end = new Carbon;
        $max = $end;
        $min = null;
        $calId = 'logCal';
        $oCal = DateRangePickerHelper::init($calId, $start, $end, $min, $max, ['drops' => 'down']);

        return view('home', [
            'XmlDatas' => $XmlDatas,
            'calendar' => $oCal,
        ]);
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

        return [$startDate, $endDate];
    }
}
