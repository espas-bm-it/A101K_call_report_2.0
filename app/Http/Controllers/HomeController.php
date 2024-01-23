<?php

namespace App\Http\Controllers;

use App\Models\XmlData;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

        $XmlDatas = XmlData::sortable($sortableColumns)->orderBy('Date', 'desc')->orderBy('Time', 'desc')->paginate(10);

        return view('home', compact('XmlDatas'));
    }
}
