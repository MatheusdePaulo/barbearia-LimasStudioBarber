<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Link do checkout da InfinitePay (pagamento integral pelo site)
            if (!Schema::hasColumn('appointments', 'payment_url')) {
                $table->text('payment_url')->nullable()->after('payment_id');
            }
            // Cupom usado no agendamento, pro barbeiro aplicar o desconto quando o pagamento é presencial
            if (!Schema::hasColumn('appointments', 'coupon_code')) {
                $table->string('coupon_code', 30)->nullable()->after('payment_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['payment_url', 'coupon_code']);
        });
    }
};
