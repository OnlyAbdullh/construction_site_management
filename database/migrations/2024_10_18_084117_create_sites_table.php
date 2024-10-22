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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('coordinates');
            $table->date('commissioning_date');
            $table->date('start_date');
            $table->enum('delivery_status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->enum('financial_closure_status', ['open', 'closed'])->default('open');
            $table->decimal('capital', 15);
            $table->decimal('sale_price', 15)->nullable();
            $table->decimal('profit_or_loss_ratio', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
