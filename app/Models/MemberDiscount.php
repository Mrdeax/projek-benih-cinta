<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDiscount extends Model
{
    protected $fillable = ['minimum_purchase', 'discount_percentage', 'description'];

    public static function getDiscount($totalPurchase)
    {
        $discount = MemberDiscount::where('minimum_purchase', '<=', $totalPurchase)
            ->orderBy('minimum_purchase', 'desc')
            ->first();
        
        return $discount ? $discount->discount_percentage : 0;
    }
}