<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_no','subtotal','tax','total','payment_method'];

    public function items()
    {
        return $this->hasMany(\App\Models\SaleItem::class);
    }
}
