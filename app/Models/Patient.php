<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    public function title()
    {
        return $this->belongsTo(Title::class, 'title_fk_id');
    }
    public function caseReports()
    {
        return $this->hasMany(CaseReport::class, 'patient_fk_id', 'id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_fk_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_fk_id');
    }
    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class, 'blood_group_fk_id');
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            $lastPatient = static::orderBy('patient_id', 'desc')->first();
            $lastNumber = (int) str_replace('PAT', '', $lastPatient->patient_id ?? 'PAT0000');
            $model->patient_id = 'PAT' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
