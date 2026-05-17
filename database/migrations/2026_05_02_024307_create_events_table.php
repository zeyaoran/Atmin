<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('artist_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');

            // split location
            $table->string('city');
            $table->string('country');

            $table->string('category')->nullable();
            $table->string('status')->nullable();

            $table->dateTime('date')->nullable();
            $table->decimal('price', 10, 2)->nullable();

            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};