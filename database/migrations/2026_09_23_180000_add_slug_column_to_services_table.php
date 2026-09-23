<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A 2026_05_09_030449_add_slug_to_services_table (vinda do Nathan) está vazia:
     * lá a coluna era criada por outra migration que não veio. Adiciona o slug aqui,
     * só se ainda não existir.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('services', 'slug')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('services', 'slug')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
