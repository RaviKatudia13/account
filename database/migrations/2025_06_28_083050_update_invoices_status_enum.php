<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // First, we need to modify the enum to include 'Partial'
            // Since MySQL doesn't allow direct enum modification, we'll use a workaround
            $table->string('status_temp')->default('Due');
        });

        // Copy data from old status column to temp column
        DB::statement("UPDATE invoices SET status_temp = status");

        // Drop the old enum column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Create new enum column with 'Partial' included
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', ['Paid', 'Due', 'Partial'])->default('Due')->after('total');
        });

        // Copy data back from temp column
        DB::statement("UPDATE invoices SET status = status_temp");

        // Drop the temp column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Revert back to original enum without 'Partial'
            $table->string('status_temp')->default('Due');
        });

        // Copy data from current status column to temp column
        DB::statement("UPDATE invoices SET status_temp = CASE WHEN status = 'Partial' THEN 'Due' ELSE status END");

        // Drop the current enum column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Create original enum column
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', ['Paid', 'Due'])->default('Due')->after('total');
        });

        // Copy data back from temp column
        DB::statement("UPDATE invoices SET status = status_temp");

        // Drop the temp column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status_temp');
        });
    }
};
