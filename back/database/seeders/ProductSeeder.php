<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Paracetamol 500mg', 'description' => 'Analgésico y antipirético', 'price' => 2.50, 'stock' => 200],
            ['name' => 'Ibuprofeno 400mg', 'description' => 'Antiinflamatorio no esteroideo', 'price' => 3.20, 'stock' => 150],
            ['name' => 'Amoxicilina 500mg', 'description' => 'Antibiótico de amplio espectro', 'price' => 8.50, 'stock' => 100],
            ['name' => 'Omeprazol 20mg', 'description' => 'Protector gástrico', 'price' => 5.00, 'stock' => 120],
            ['name' => 'Loratadina 10mg', 'description' => 'Antihistamínico contra alergias', 'price' => 4.00, 'stock' => 80],
            ['name' => 'Vitamina C 1g', 'description' => 'Suplemento vitamínico', 'price' => 6.50, 'stock' => 90],
            ['name' => 'Suero Oral 500ml', 'description' => 'Rehidratación oral', 'price' => 3.80, 'stock' => 60],
            ['name' => 'Alcohol 70%', 'description' => 'Desinfectante de uso general', 'price' => 7.00, 'stock' => 50],
            ['name' => 'Mascarilla KN95', 'description' => 'Protección respiratoria', 'price' => 2.00, 'stock' => 300],
            ['name' => 'Gel antibacterial 250ml', 'description' => 'Higiene de manos', 'price' => 4.50, 'stock' => 110],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
