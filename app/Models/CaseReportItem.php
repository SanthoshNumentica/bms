<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseReportItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
    'documents' => 'array',
];

    public function caseReport()
    {
        return $this->belongsTo(CaseReport::class);
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
