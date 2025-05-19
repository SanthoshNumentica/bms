<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CaseReportItem extends Model
{
    use HasFactory;
    protected $fillable = ['scan_type_id', 'scan_id', 'documents', 'case_report_id','remarks'];

    protected $casts = [
    'documents' => 'array',
];

    public function caseReport()
{
    return $this->belongsTo(CaseReport::class, 'case_report_id');
}

    public function scanType()
    {
        return $this->belongsTo(ScanType::class);
    }
    public function scan()
    {
        return $this->belongsTo(Scan::class, 'scan_id');
    }
    

}
