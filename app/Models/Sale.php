<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'user_id', 'member_id', 'subtotal', 'discount_amount', 'total_amount', 'payment_method', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function details()
    {
        return $this->hasMany(SalesDetail::class);
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $lastInvoice = Sale::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if (!$lastInvoice) {
            return $prefix . '001';
        }
        
        $number = (int) substr($lastInvoice->invoice_number, -3) + 1;
        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}