<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\CaseReport; // Make sure this model exists

class WhatsAppController extends Controller
{
    

    /**
     * Find Case Report by ID
     */
  public function findCaseReportById($id)
{
   $caseReport = CaseReport::with('patient')->find($id);
    print_r($caseReport);
    return;
    if (!$caseReport) {
        return response()->json(['error' => 'Case Report not found'], 404);
    }
    $basePath = asset('storage');
    foreach ($caseReport->items as $item) {
        if (is_array($item->documents)) {
            $item->documents = array_map(function ($doc) use ($basePath) {
                return $basePath . '/' . ltrim($doc, '/');
            }, $item->documents);
        }
    }
    $documents = $caseReport->items
        ->pluck('documents')
        ->flatten()
        ->filter()
    ->toArray();
    $caseReport->documents_full_path = $documents;
    if (isset($caseReport['response']) && is_string($caseReport['response'])) {
        $caseReport['response'] = json_decode($caseReport['response']);
    }
    try {
        $patientName = $caseReport->patient->name ?? 'Patient';
        // $patientPhone = $caseReport->patient->phone ?? null;
        $reportId = $caseReport->id;
        $reportDate = $caseReport->created_at->format('Y-m-d');
        // if (!$patientPhone) {
        //     return response()->json([
        //         'error' => 'Patient phone number is missing.'
        //     ], 422);
        // }
        $mobile_no = env('APP_ENV') !== 'production'
            ? env('TEST_WHATSAPP', '')
            : env('TEST_WHATSAPP');

        $message = "Hello {$patientName}, your report (ID: {$reportId}) dated {$reportDate} is available.";
        foreach ($documents as $doc) {
            $message .= "\n• Document: {$doc}";
        }
        $formData = [
            "appkey" => env('APPKEY'),
            "authkey" => env('AUTHKEY'),
            "to" => $mobile_no,
            "message" => $message,
            "file"=>$doc
        ];
        $url = filter_var(env('WHATSAPP_URL'), FILTER_SANITIZE_URL);
        $response = Http::withHeaders([
            'Accept' => '*/*',
            'appkey' => env('APPKEY'),
            'Content-Type' => 'application/json',
        ])->post($url, $formData);
        $resBody = $response->body();
        $success = str_contains(strtolower(trim($resBody)), 'true');
        Log::info('Auto WhatsApp message sent after report fetch', [
            'mobile_no' => $mobile_no,
            'message' => $message,
            'response' => $resBody,
        ]);

        return response()->json($caseReport);

    } catch (\Exception $e) {
        Log::error('Failed to send WhatsApp message', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'case_report' => $caseReport,
            'whatsapp_sent' => false,
            'error' => 'WhatsApp send failed: ' . $e->getMessage()
        ]);
    }
}



}
