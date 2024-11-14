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
        Schema::create('capital_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recordable_id'); // ID for the related material or sub-material
            $table->string('recordable_type');           // Type of the related model (material or sub-material)
            $table->decimal('capital', 15, 2);
            $table->decimal('quantity',10,2);
            $table->index(['recordable_id', 'recordable_type']);
            $table->date('entry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_histories');
    }
};
