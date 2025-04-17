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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('pickup_location');
            $table->string('destination');
            $table->enum('package_type', ['small', 'medium', 'large', 'extra_large']);
            $table->enum('delivery_type', ['standard', 'express', 'overnight']);
            $table->date('delivery_date');
            $table->text('special_instructions')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('driver_id')
            ->nullable()
            ->constrained('drivers')
            ->onDelete('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
