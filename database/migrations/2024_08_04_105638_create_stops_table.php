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
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->string('title', 150);
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('day');
            $table->unsignedTinyInteger('order');
            $table->text('food')->nullable();
            $table->string('place');
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->boolean('is_visited')->default(0);
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamps();

            $table->foreign('travel_id')
                ->references('id')
                ->on('travels')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stops');
    }
};
