<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\XmlData; // Make sure to import your model

class ReadXmlController extends Controller
{
    // xml Datei auf phpmyadmin hochladen
    public function index(Request $req)
    {
        $xmlDataString = file_get_contents(public_path('TicketCollector.xml'));

        $xmlObject = simplexml_load_string($xmlDataString);

        $json = json_encode($xmlObject);
        $phpDataArray = json_decode($json, true);

        if (isset($phpDataArray['CallAccounting']) && count($phpDataArray['CallAccounting']) > 0) {
            foreach ($phpDataArray['CallAccounting'] as $data) {
                // Create a new record in the database for each $data item
                XmlData::create([
                    "SubscriberName" => isset($data['SubscriberName']) ? $data['SubscriberName'] : null,
                    "DialledNumber" => isset($data['DialledNumber']) ? $data['DialledNumber'] : null,
                    "Date" => $data['Date'],
                    "Time" => $data['Time'],
                    "RingingDuration" => $data['RingingDuration'],
                    "CallDuration" => $data['CallDuration'],
                    "CommunicationType" => $data['CommunicationType']
                ]);
            }
            
            return response()->json(['message' => 'Data inserted successfully']);
        }

        return response()->json(['message' => 'No data found in XML file'], 400);
    }
}