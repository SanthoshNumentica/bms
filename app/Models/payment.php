<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'payment_id',
        'invoice_id',
        'patient_fk_id',
        'created_by',
        'payment_date',
        'description',
        'amount',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_fk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // ===== Generate payment_id like PAY0001 =====
            $lastPayment = static::orderBy('payment_id', 'desc')->first();
            $lastPaymentNumber = (int) str_replace('PAY', '', $lastPayment->payment_id ?? 'PAY0000');
            $model->payment_id = 'PAY' . str_pad($lastPaymentNumber + 1, 4, '0', STR_PAD_LEFT);

            // ===== Generate invoice_id like INV0001 =====
            $lastInvoice = static::orderBy('invoice_id', 'desc')->first();
            $lastInvoiceNumber = (int) str_replace('INV', '', $lastInvoice->invoice_id ?? 'INV0000');
            $model->invoice_id = 'INV' . str_pad($lastInvoiceNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
