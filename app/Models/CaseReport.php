<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CaseReport extends Model
{
    use HasFactory;

   protected $fillable = ['case_id', 'patient_fk_id', 'doc_ref_fk_id', 'description', 'remarks', 'status'];

    

    // ✅ ADD THIS CASTING
    protected $casts = [
        'documents' => 'array',
    ];

    public function patient()
{
    return $this->belongsTo(Patient::class, 'patient_fk_id', 'id');
}



    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doc_ref_fk_id');
    }

     public function items()
    {
        return $this->hasMany(CaseReportItem::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $lastCase = static::orderBy('case_id', 'desc')->first();
            $lastNumber = (int) str_replace('CAS', '', $lastCase->case_id ?? 'CAS000');
            $model->case_id = 'CAS' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }
   


}
