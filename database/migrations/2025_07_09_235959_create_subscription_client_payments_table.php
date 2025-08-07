<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_client_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('subscriptions_client_list')->onDelete('cascade');
            $table->enum('gst_type', ['GST', 'IGST', 'NOGST']);
            $table->string('invoice_number')->unique();
            $table->string('gstin')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('gst_amount', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_client_payments');
    }
}; 