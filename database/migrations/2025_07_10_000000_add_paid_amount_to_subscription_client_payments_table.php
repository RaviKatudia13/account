<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_client_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_client_payments', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0)->after('total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_client_payments', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_client_payments', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
}; 