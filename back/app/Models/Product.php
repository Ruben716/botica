<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];

    // Relación con SaleItem (un producto puede estar en muchos detalles de venta)
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
