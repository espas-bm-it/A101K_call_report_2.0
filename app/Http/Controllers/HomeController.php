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



        $XmlDatas = XmlData::sortable($sortableColumns)->select('SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType')
            ->whereNotIn('CommunicationType', ['BreakIn', 'FacilityRequest'])
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->paginate(10);

       
        return view('home', [
            'XmlDatas' => $XmlDatas,
            
        ]);
    }


}
