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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('barber_id')->nullable();
            $table->unsignedBigInteger('service_id');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('start');
            $table->timestamp('end');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->foreign('barber_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
            
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
