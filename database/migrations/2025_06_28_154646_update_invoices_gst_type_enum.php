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
            // First, we need to modify the enum to include 'Non-GST'
            // Since MySQL doesn't allow direct enum modification, we'll use a workaround
            $table->string('gst_type_temp')->default('GST');
        });

        // Copy data from old gst_type column to temp column
        DB::statement("UPDATE invoices SET gst_type_temp = gst_type");

        // Drop the old enum column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('gst_type');
        });

        // Create new enum column with 'Non-GST' included
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('gst_type', ['Non-GST', 'GST', 'IGST'])->default('GST')->after('subtotal');
        });

        // Copy data back from temp column
        DB::statement("UPDATE invoices SET gst_type = gst_type_temp");

        // Drop the temp column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('gst_type_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Revert back to original enum without 'Non-GST'
            $table->string('gst_type_temp')->default('GST');
        });

        // Copy data from current gst_type column to temp column
        DB::statement("UPDATE invoices SET gst_type_temp = CASE WHEN gst_type = 'Non-GST' THEN 'GST' ELSE gst_type END");

        // Drop the current enum column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('gst_type');
        });

        // Create original enum column
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('gst_type', ['GST', 'IGST'])->default('GST')->after('subtotal');
        });

        // Copy data back from temp column
        DB::statement("UPDATE invoices SET gst_type = gst_type_temp");

        // Drop the temp column
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('gst_type_temp');
        });
    }
};
