<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;
    protected $guarded=[];
     protected $fillable = ['name', 'scan_type_fk_id'];

    public function scanType()
    {
        return $this->belongsTo(ScanType::class, 'scan_type_fk_id');
    }
   
}
