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
                // Variable für die Filtrierung festsetzen und auf Funktion verweisen
                $communicationType = isset($data['CommunicationType']) ? $this->getCommunicationType($data['CommunicationType']) : 'unknown';
                $callDuration = isset($data['CallDuration']) ? $this->getCallDuration($data['CallDuration']) : 'unknown';
            
                // Check for conditions to set CallStatus and SubscriberName
                if ($data['CommunicationType'] === 'FacilityRequest') {
                    // Calls with no DialledNumber, 00:00:00 CallDuration, 00:00:00 RingingDuration, and CommunicationType "FacilityRequest"
                    $callStatus = 'Facility Request';
                    $subscriberName = empty($data['SubscriberName']) ? 'Facility Request' : $data['SubscriberName'];
                } elseif ($data['CommunicationType'] === 'BreakIn') {
                    // Calls with 00:00:00 CallDuration, 00:00:00 RingingDuration, and CommunicationType "BreakIn"
                    $callStatus = 'Break In';
                    $subscriberName = empty($data['SubscriberName']) ? 'Break In' : $data['SubscriberName'];
                } elseif (!empty($data['SubscriberName']) && $data['CallDuration'] === '00:00:00' && $data['RingingDuration'] === '00:00:00' && !in_array($data['CommunicationType'], ['FacilityRequest', 'BreakIn'])) {
                    // Calls with SubscriberName, 00:00:00 CallDuration, 00:00:00 RingingDuration, and CommunicationType not "FacilityRequest" or "BreakIn"
                    $callStatus = 'Verpasst';
                    $subscriberName = $data['SubscriberName'];
                } else {
                    // All other cases
                    $callStatus = $callDuration;
                    $subscriberName = isset($data['SubscriberName']) ? $data['SubscriberName'] : null;
                }
            
                // Create a new record in the database for each $data item
                XmlData::create([
                    "SubscriberName" => $subscriberName,
                    "DialledNumber" => isset($data['DialledNumber']) ? $data['DialledNumber'] : null,
                    "Date" => $data['Date'],
                    "Time" => $data['Time'],
                    "RingingDuration" => $data['RingingDuration'],
                    "CallDuration" => $data['CallDuration'],
                    "CallStatus" => $callStatus,
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
            return 'Ausgehend';
        }
        elseif ($providedCommunicationType === 'IncomingPrivate' ||
            $providedCommunicationType ===  'IncomingTransit' ||
            $providedCommunicationType ===  'IncomingTransferPrivate' ||
            $providedCommunicationType ===  'IncomingTransferTransit'){
            return 'Eingehend';
        }
        elseif($providedCommunicationType === 'BreakIn'){
            return 'BreakIn';
        }
        elseif($providedCommunicationType === 'FacilityRequest') {
            return 'FacilityRequest';
        }
        else{
            return 'Unbekannt';
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