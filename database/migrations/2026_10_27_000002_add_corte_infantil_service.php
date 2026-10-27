<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Serviço "Corte Infantil" na escolha de serviços do agendamento.
    // O preço pode ser ajustado depois no painel (Admin > Serviços).
    public function up(): void
    {
        if (DB::table('services')->where('slug', 'infantil')->exists()) {
            return;
        }

        DB::table('services')->insert([
            'name'        => 'Corte Infantil',
            'slug'        => 'infantil',
            'description' => 'Corte pra criançada, com paciência e capricho.',
            'price'       => 30.00,
            'duration'    => 30,
            'image'       => 'infantil.svg',
            'is_combo'    => false,
            'is_promo'    => false,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'infantil')->delete();
    }
};
