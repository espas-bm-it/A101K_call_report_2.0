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
    public function index()
    {
        return view('home', [
            'XmlDatas' => XmlData::orderByDesc('Date')->orderByDesc('Time')->paginate(10)
        ]);
    }
}
