<?php

namespace App\Http\Controllers;

use App\Models\XmlData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    use Sortable;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    // index function returns the view home and gives with it a paginated query of all objects in the database

    public function index(Request $request)
    {
        

        $sortableColumns = ['SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];

        $latestCall = XmlData::latest('Date')->first();

        if ($latestCall) {
            $endDate = Carbon::parse($latestCall->Date);
            $startDate = $endDate->copy()->subDays(5)->startOfDay();
        } else {
            // Fallback, wenn keine Anrufe vorhanden sind
            $startDate = Carbon::now()->subDays(5)->startOfDay();
            $endDate = Carbon::now();
        }

        $XmlDatas = XmlData::sortable($sortableColumns)->select('SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType')
            
            ->whereNotIn('CommunicationType', ['BreakIn', 'FacilityRequest'])
            
            ->orderBy('Date', 'desc')
            ->orderBy('Time', 'desc')
            ->paginate(10);

        return view('home', compact('XmlDatas'));
    }
}
