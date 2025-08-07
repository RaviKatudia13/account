<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_mode')) {
                $table->unsignedBigInteger('payment_mode')->nullable()->after('id');
                $table->foreign('payment_mode')->references('id')->on('payment_methods')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_mode')) {
                $table->dropForeign(['payment_mode']);
                $table->dropColumn('payment_mode');
            }
        });
    }
}; 