<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'vendor_due',
            'employee_due',
        ];
        foreach ($tables as $table) {
            // 1. Add user_id as nullable
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
            // 2. Assign all existing rows to the first user
            $firstUserId = DB::table('users')->orderBy('id')->value('id');
            if ($firstUserId) {
                DB::table($table)->whereNull('user_id')->update(['user_id' => $firstUserId]);
            }
            // 3. Make user_id not nullable and add foreign key
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'vendor_due',
            'employee_due',
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
}; 