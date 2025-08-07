<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_client_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_client_payments', 'status')) {
                $table->enum('status', ['Paid', 'Due', 'Partial'])->default('Due')->after('paid_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('subscription_client_payments', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_client_payments', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}; 