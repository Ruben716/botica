<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Relación con ventas (un cliente puede tener muchas ventas)
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
