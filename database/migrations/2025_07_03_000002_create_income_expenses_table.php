<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('income_expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']);
            $table->unsignedBigInteger('emp_vendor_id')->nullable();
            $table->enum('emp_vendor_type', ['employee', 'vendor']);
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('inc_exp_category_id');
            $table->foreign('inc_exp_category_id')->references('id')->on('inc_exp_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('income_expenses');
    }
}; 