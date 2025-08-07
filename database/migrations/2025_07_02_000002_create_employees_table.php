<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number');
            $table->string('address');
            $table->string('designation');
            $table->date('join_date');
            $table->unsignedBigInteger('employee_category_id');
            $table->foreign('employee_category_id')->references('id')->on('employee_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}; 