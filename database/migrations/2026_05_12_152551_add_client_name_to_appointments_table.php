<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // client_name será usado quando o administrador cadastrar alguém manualmente
            // (a 2026_05_06_180634 já cria essa coluna; só adiciona se ainda não existir)
            if (! Schema::hasColumn('appointments', 'client_name')) {
                $table->string('client_name')->nullable()->after('service_id');
            }
            // Tornar o user_id opcional (nullable) para agendamentos manuais
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
