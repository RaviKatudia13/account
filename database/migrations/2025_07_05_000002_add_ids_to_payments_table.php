<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove old due columns if they exist
            if (Schema::hasColumn('payments', 'vendor_due_id')) {
                $table->dropColumn('vendor_due_id');
            }
            if (Schema::hasColumn('payments', 'employee_due_id')) {
                $table->dropColumn('employee_due_id');
            }
            // Add new direct reference columns if not present
            if (!Schema::hasColumn('payments', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('payments', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->nullable()->after('vendor_id');
            }
            // If you want a foreign key constraint, uncomment the next line:
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'vendor_id')) {
                $table->dropColumn('vendor_id');
            }
            if (Schema::hasColumn('payments', 'employee_id')) {
                $table->dropColumn('employee_id');
            }
            $table->unsignedBigInteger('vendor_due_id')->nullable()->after('invoice_id');
            $table->unsignedBigInteger('employee_due_id')->nullable()->after('vendor_due_id');
        });
    }
}; 