<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'description', 'price', 'stock', 'minimum_stock'];

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class);
    }

    public function isStockLow()
    {
        return $this->stock <= $this->minimum_stock;
    }
}