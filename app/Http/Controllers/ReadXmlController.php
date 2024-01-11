<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\XmlData;

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
                // Variable für die Filtrierung festsetzen und auf funktion verweisen
                $communicationType = isset($data['CommunicationType']) ? $this->getCommunicationType($data['CommunicationType']) : 'unknown';
                $callDuration = isset($data['CallDuration']) ? $this->getCallDuration($data['CallDuration']) : 'unknown';

                // Create a new record in the database for each $data item
                XmlData::create([
                    "SubscriberName" => isset($data['SubscriberName']) ? $data['SubscriberName'] : null,
                    "DialledNumber" => isset($data['DialledNumber']) ? $data['DialledNumber'] : null,
                    "Date" => $data['Date'],
                    "Time" => $data['Time'],
                    "RingingDuration" => $data['RingingDuration'],
                    "CallDuration" => $data['CallDuration'],
                    "CallStatus" => $callDuration,
                    "CommunicationType" => $communicationType
                ]);
            }

            return response()->json(['message' => 'Data inserted successfully']);
        }

        return response()->json(['message' => 'No data found in XML file'], 400);
    }
    // funktion für die Filtrierung
    private function getCommunicationType($providedCommunicationType){
        if ($providedCommunicationType === 'OutgoingPrivate' ||
            $providedCommunicationType === 'OutgoingTransferTransit' ||
            $providedCommunicationType === 'OutgoingTransferPrivate' ||
            $providedCommunicationType === 'OutgoingTransit'){
            return 'ausgehend';
        }
        elseif ($providedCommunicationType === 'IncomingPrivate' ||
            $providedCommunicationType ===  'IncomingTransit' ||
            $providedCommunicationType ===  'IncomingTransferPrivate' ||
            $providedCommunicationType ===  'IncomingTransferTransit'){
            return 'eingehend';
        }
        elseif($providedCommunicationType === 'BreakIn'){
            return 'BreakIn';
        }
        elseif($providedCommunicationType === 'FacilityRequest') {
            return 'FacilityRequest';
        }
        else{
            return 'unknown';
        }
    }

    private function getCallDuration($providedCallDuration){
        if ($providedCallDuration === '00:00:00'){
            return 'verpasst';
        }
        else{
            return 'angenommen';
        }
    }

}