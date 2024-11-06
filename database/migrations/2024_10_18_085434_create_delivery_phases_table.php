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
        Schema::create('delivery_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('preliminary_invoice')->default(false);
            $table->enum('technical_receipt', ['delivered', 'delivered_with_notes', 'not_delivered'])->default('not_delivered');
            $table->boolean('technical_receipt_invoice')->default(false);
            $table->enum('final_receipt', ['delivered', 'delivered_with_notes', 'not_delivered'])->default('not_delivered');
            $table->boolean('final_receipt_invoice')->default(false);
            $table->enum('delivery_status', ['delivered', 'in_progress', 'not_delivered'])->default('not_delivered');
            $table->enum('delivery_status', ['delivered', 'in_progress', 'not_delivered'])->default('not_delivered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_phases');
    }
};
