<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories
        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
        // Vendor Categories
        Schema::table('vendor_categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
        // Income/Expense Categories
        Schema::table('inc_exp_categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
        // Employee Categories
        Schema::table('employee_categories', function (Blueprint $table) {
            $table->unique(['user_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
        Schema::table('vendor_categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
        Schema::table('inc_exp_categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
        Schema::table('employee_categories', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
    }
}; 