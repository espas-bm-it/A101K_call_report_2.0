<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\XmlData;
use App\Models\Config;



class ReadXmlController extends Controller
{
    // xml Datei auf phpmyadmin hochladen
    public function index(Request $req)
    {
        // Retrieve the XML file path from the configs table
        $config = Config::find(1); // Assuming there's only one configuration record, adjust as needed

        if (!$config) {
            return response()->json(['message' => 'Configuration not found'], 400);
        }

        $xmlFilePath = $config->path;

        // Check if the file exists at the specified path
        if (!file_exists($xmlFilePath)) {
            return response()->json(['message' => 'XML file not found at the specified path'], 400);
        }

        $xmlDataString = file_get_contents($xmlFilePath);

        $xmlObject = simplexml_load_string($xmlDataString);

        $json = json_encode($xmlObject);
        $phpDataArray = json_decode($json, true);

        if (isset($phpDataArray['CallAccounting']) && count($phpDataArray['CallAccounting']) > 0) {
            foreach ($phpDataArray['CallAccounting'] as $data) {
                // Variable für die Filtrierung festsetzen und auf Funktion verweisen
                $communicationType = isset($data['CommunicationType']) ? $this->getCommunicationType($data['CommunicationType']) : 'unknown';
                $callDuration = isset($data['CallDuration']) ? $this->getCallDuration($data['CallDuration'], $communicationType) : 'unknown';

                // Check for conditions to set CallStatus and SubscriberName
                if ($data['CommunicationType'] === 'FacilityRequest') {
                    // Calls with no DialledNumber, 00:00:00 CallDuration, 00:00:00 RingingDuration, and CommunicationType "FacilityRequest"
                    continue;
                } elseif ($data['CommunicationType'] === 'BreakIn') {
                    // Calls with 00:00:00 CallDuration, 00:00:00 RingingDuration, and CommunicationType "BreakIn"
                    continue;
                } elseif (in_array($data['CommunicationType'], ['OutgoingPrivate', 'OutgoingTransferTransit', 'OutgoingTransferPrivate', 'OutgoingTransit'])) {
                    // Calls with CommunicationType "OutgoingPrivate", "OutgoingTransferTransit", "OutgoingTransferPrivate", "OutgoingTransit"
                    $callStatus = '-';
                    $subscriberName = isset($data['SubscriberName']) ? $data['SubscriberName'] : null;
                } elseif (in_array($data['CommunicationType'], ['IncomingPrivate', 'IncomingTransit', 'IncomingTransferPrivate', 'IncomingTransferTransit']) && $data['CallDuration'] !== '00:00:00') {
                    // Calls with CommunicationType "IncomingPrivate", "IncomingTransit", "IncomingTransferPrivate", "IncomingTransferTransit" and non-zero CallDuration
                    $callStatus = 'Angenommen';
                    $subscriberName = isset($data['SubscriberName']) ? $data['SubscriberName'] : null;
                } elseif (in_array($data['CommunicationType'], ['IncomingPrivate', 'IncomingTransit', 'IncomingTransferPrivate', 'IncomingTransferTransit']) && $data['CallDuration'] === '00:00:00') {
                    // Calls with  00:00:00 CallDuration,  and CommunicationType "IncomingPrivate", "IncomingTransit", "IncomingTransferPrivate", "IncomingTransferTransit
                    $callStatus = 'Verpasst';
                    $subscriberName = isset($data['SubscriberName']) ? $data['SubscriberName'] : null;
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
    private function getCommunicationType($providedCommunicationType)
    {
        if (
            $providedCommunicationType === 'OutgoingPrivate' ||
            $providedCommunicationType === 'OutgoingTransferPrivate'
        ) {
            return 'TSAusgehend';
        } elseif (
            $providedCommunicationType === 'OutgoingTransferTransit' ||
            $providedCommunicationType === 'OutgoingTransit'
        ) {
            return 'PAusgehend';
        } elseif (
            $providedCommunicationType === 'IncomingPrivate' ||
            $providedCommunicationType ===  'IncomingTransit' ||
            $providedCommunicationType ===  'IncomingTransferPrivate' ||
            $providedCommunicationType ===  'IncomingTransferTransit'
        ) {
            return 'Eingehend';
        } elseif ($providedCommunicationType === 'BreakIn') {
            return 'BreakIn';
        } elseif ($providedCommunicationType === 'FacilityRequest') {
            return 'FacilityRequest';
        } else {
            return 'Unbekannt';
        }
    }

    private function getCallDuration($providedCallDuration, $communicationType)
    {
        if ($providedCallDuration === '00:00:00' && $communicationType === 'Eingehend') {
            return 'Angenommen';
        } elseif ($providedCallDuration === '00:00:00') {
            return 'verpasst';
        } else {
            return 'angenommen';
        }
    }
}
