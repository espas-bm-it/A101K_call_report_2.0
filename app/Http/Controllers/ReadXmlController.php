<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\XmlData;
use App\Models\Config;
use SimpleXMLElement;

class ReadXmlController extends Controller
{
    // xml Datei auf phpmyadmin hochladen
    public function index(Request $req)
    {
        // Retrieve the XML file path from the configs table
        $config = Config::getConfigData();

        if (!$config) {
            return response()->json(['message' => 'Configuration not found'], 400);
        }

        $xmlFilePath = $config->path;

        // Check if the file exists at the specified path
        if (!file_exists($xmlFilePath)) {
            return response()->json(['message' => 'XML file not found '], 400);
        }

        // Load the XML file
        $xmlObject = simplexml_load_file($xmlFilePath);

        if (!$xmlObject) {
            return response()->json(['message' => 'Failed to load XML file'], 400);
        }


        // Iterate over each CallAccounting element in the XML
        foreach ($xmlObject->CallAccounting as $data) {
            $communicationType = (string)$data->CommunicationType;
            $callDuration = (string)$data->CallDuration;

            // Check for conditions to set CallStatus and SubscriberName
            if (
                $communicationType === 'FacilityRequest' ||
                ($communicationType === 'BreakIn' && $callDuration === '00:00:00')
            ) {
                // Skip records based on specified conditions
                continue;
            }

            if (in_array($communicationType, ['OutgoingPrivate', 'OutgoingTransferTransit', 'OutgoingTransferPrivate', 'OutgoingTransit'])) {
                $callStatus = '-';
                $subscriberName = (string)$data->SubscriberName;
            } elseif (in_array($communicationType, ['IncomingPrivate', 'IncomingTransit', 'IncomingTransferPrivate', 'IncomingTransferTransit']) && $callDuration !== '00:00:00') {
                $callStatus = 'Angenommen';
                $subscriberName = (string)$data->SubscriberName;
            } elseif (in_array($communicationType, ['IncomingPrivate', 'IncomingTransit', 'IncomingTransferPrivate', 'IncomingTransferTransit']) && $callDuration === '00:00:00') {
                $callStatus = 'Verpasst';
                $subscriberName = (string)$data->SubscriberName;
            } else {
                // Default case if none of the above conditions match
                $callStatus = $this->getCallDuration($callDuration, $communicationType);
                $subscriberName = (string)$data->SubscriberName;
            }

            // Create a new record in the database for each $data item
            XmlData::create([
                "SubscriberName" => $subscriberName,
                "DialledNumber" => (string)$data->DialledNumber,
                "Date" => (string)$data->Date,
                "Time" => (string)$data->Time,
                "RingingDuration" => (string)$data->RingingDuration,
                "CallDuration" => $callDuration,
                "CallStatus" => $callStatus,
                "CommunicationType" => $communicationType
            ]);
        }

        return response()->json(['message' => 'Data inserted successfully']);
    }

    // Function to determine call duration status based on provided duration and communication type
    private function getCallDuration($providedCallDuration, $communicationType)
    {
        if ($providedCallDuration === '00:00:00' && $communicationType === 'Eingehend') {
            return 'Angenommen';
        } elseif ($providedCallDuration === '00:00:00') {
            return 'Verpasst';
        } else {
            return 'Angenommen';
        }
    }
}
