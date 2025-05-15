<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function whatsAppSend(Request $request)
    {
        $patientName = $request->input('patient_name');
        $patientPhone = $request->input('patient_phone');
        $reportId = $request->input('report_id');
        $reportDate = $request->input('report_date');
        $documents = $request->input('documents', []);
        $mobile_no = $patientPhone;
        if (env('APP_ENV') !== 'production') {
            $mobile_no = env('TEST_WHATSAPP', $mobile_no); 
        }
        sleep(1); // optional delay
        $message = "Hello {$patientName}, your report (ID: {$reportId}) dated {$reportDate} is available.";
        if (!empty($documents) && is_array($documents)) {
            foreach ($documents as $doc) {
                $message .= "\n• Document: " . (is_string($doc) ? $doc : json_encode($doc));
            }
        }
        $formData = [
            "appkey" => env('APPKEY'),
            "authkey" => env('AUTHKEY'),
            "to" => $mobile_no,
            "message" => $message,
        ];
        $url = env('WHATSAPP_URL');
        $req_url = filter_var($url, FILTER_SANITIZE_URL);
        $response = Http::withHeaders([
            'Accept' => '*/*',
            'appkey' => env('APPKEY'),
            'Content-Type' => 'application/json',
        ])->post($req_url, $formData);
        $resBody = $response->body();
        $success = str_contains(strtolower(trim($resBody)), 'true');
        Log::info('WhatsApp message send attempt', [
            'mobile_no' => $mobile_no,
            'message' => $message,
            'response' => $resBody
        ]);
        return response()->json([
            'status' => $success,
            'response' => $resBody
        ]);
    }
}
