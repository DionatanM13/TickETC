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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("title");
            $table->dateTime("date");
            $table->dateTime("finalDate")->nullable();
            $table->text("city");
            $table->text("local");
            $table->text("size");
            $table->boolean("private");
            $table->text("dominio")->nullable();
            $table->text("description");
            $table->json('categories');
            $table->string("image");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
