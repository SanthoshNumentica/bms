<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function title()
    {
        return $this->belongsTo(Title::class, 'title_fk_id');
    }
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_fk_id');
    }
  
    protected static function booted()
    {
        static::creating(function ($model) {
            // Get the last doctor_id from the database
            $lastPatient = static::orderBy('doctor_id', 'desc')->first();

            // Extract the numeric part and increment it by 1
            $lastNumber = (int) str_replace('DOC', '', $lastPatient->doctor_id ?? 'DOC0000');

            // Generate the new doctor_id with padding
            $model->doctor_id = 'DOC' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
