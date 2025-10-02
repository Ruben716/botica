<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juanperez@mail.com',
                'phone' => '987654321',
                'address' => 'Av. Los Incas 123'
            ],
            [
                'name' => 'María López',
                'email' => 'marialopez@mail.com',
                'phone' => '956321478',
                'address' => 'Jr. Puno 456'
            ],
            [
                'name' => 'Carlos Ramírez',
                'email' => 'carlosr@mail.com',
                'phone' => '912345678',
                'address' => 'Calle Arequipa 789'
            ],
            [
                'name' => 'Ana Torres',
                'email' => 'anatorres@mail.com',
                'phone' => '998877665',
                'address' => 'Av. La Cultura 321'
            ],
            [
                'name' => 'Pedro Sánchez',
                'email' => 'pedros@mail.com',
                'phone' => '987112233',
                'address' => 'Jr. Tacna 987'
            ],
            [
                'name' => 'Lucía Fernández',
                'email' => 'luciaf@mail.com',
                'phone' => '999111222',
                'address' => 'Urb. Los Andes Mz A Lt 10'
            ],
            [
                'name' => 'Jorge Castillo',
                'email' => 'jorgec@mail.com',
                'phone' => '933444555',
                'address' => 'Av. El Sol 111'
            ],
            [
                'name' => 'Rosa García',
                'email' => 'rosag@mail.com',
                'phone' => '922333444',
                'address' => 'Calle Moquegua 222'
            ],
            [
                'name' => 'Miguel Díaz',
                'email' => 'migueld@mail.com',
                'phone' => '911222333',
                'address' => 'Av. Titicaca 333'
            ],
            [
                'name' => 'Elena Vargas',
                'email' => 'elenav@mail.com',
                'phone' => '988776655',
                'address' => 'Jr. Lima 444'
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
