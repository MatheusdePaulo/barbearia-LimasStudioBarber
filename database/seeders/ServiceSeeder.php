<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Serviços do Lima's: Cabelo, Barba e Combo (cabelo + barba).
     * Usa o slug como chave, então pode rodar de novo sem duplicar
     * (atualiza os existentes). Preço e duração dá pra mudar depois em Serviços no painel.
     */
    public function run(): void
    {
        $services = [
            ['slug' => 'cabelo', 'name' => 'Cabelo', 'description' => 'Corte clássico ou moderno, tesoura e máquina.',   'price' => 30, 'duration' => 30, 'image' => 'masculino.webp',  'is_combo' => false],
            ['slug' => 'barba',  'name' => 'Barba',  'description' => 'Alinhamento com navalha e toalha quente.',        'price' => 25, 'duration' => 30, 'image' => 'barba.webp',      'is_combo' => false],
            ['slug' => 'combo',  'name' => 'Combo',  'description' => 'Cabelo + Barba. Estilo completo em um só lugar.', 'price' => 50, 'duration' => 60, 'image' => 'CorteBarba.webp', 'is_combo' => true],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
