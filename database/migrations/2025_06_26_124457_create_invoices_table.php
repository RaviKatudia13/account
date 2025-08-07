<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->json('items'); // [{description, rate, gst_type, gst_percent, amount}]
            $table->decimal('subtotal', 12, 2);
            $table->enum('gst_type', ['GST', 'IGST']);
            $table->decimal('gst_amount', 12, 2);
            $table->decimal('total', 12, 2);
            $table->enum('status', ['Paid', 'Due', 'Partial'])->default('Due');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
