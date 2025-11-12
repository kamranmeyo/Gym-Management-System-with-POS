<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','price','stock','description'];

    public function saleItems()
    {
        return $this->hasMany(\App\Models\SaleItem::class);
    }
}
