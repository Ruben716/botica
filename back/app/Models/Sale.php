<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'total',
    ];

    // Relación con cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relación con detalle de ventas
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
