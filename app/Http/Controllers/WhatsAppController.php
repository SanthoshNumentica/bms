<?php

namespace App\Http\Controllers;

use App\Models\CaseReport;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function sendDocumentToWhatsApp($recordId)
    {
        $caseReport = CaseReport::find($recordId);

        // New logic commented for now
        /*
        if ($caseReport && $caseReport->documents && $caseReport->patient->whatsapp_number) {
            $whatsappNumber = $caseReport->patient->whatsapp_number;

            $documents = is_array($caseReport->documents)
                ? $caseReport->documents
                : json_decode($caseReport->documents, true);

            $documentUrl = null;
            if (!empty($documents)) {
                $documentPath = $documents[0];
                $documentUrl = asset("storage/{$documentPath}");
            }

            if (!$documentUrl) {
                return response()->json(['message' => 'Document URL not found.'], 400);
            }
        */

        // Old logic restored
        if ($caseReport && $caseReport->documents && $caseReport->patient->whatsapp_number) {
            $whatsappNumber = $caseReport->patient->whatsapp_number;
            $documentUrl = $caseReport->documents->first()->url;  // Old assumption of collection with `url`

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://acs.agoo.in/api/create-message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'appkey' => '5d34d98c-c81b-4e1e-a8f5-74c24e0d17a8',
                    'authkey' => 'jSvVJO1Lp3u07oDKDESCrDxyBoV7LSZ0UrMCT5t642H15j9YNX',
                    'to' => '919384579716',
                    'message' => 'Here is your document.',
                    'file' => 'https://www.learningcontainer.com/wp-content/uploads/2019/09/sample-pdf-file.pdf',
                    'sandbox' => 'false',
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            return response()->json([
                'message' => 'Document sent via WhatsApp!',
                'response' => $response,
            ]);
        }
        return response()->json(['message' => 'No document found or invalid number.'], 400);
    }

}

