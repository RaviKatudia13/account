<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
    }
}; 