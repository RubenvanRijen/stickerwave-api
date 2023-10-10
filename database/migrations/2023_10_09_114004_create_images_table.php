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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('mime');
            $table->binary('data'); // Use binary for image data
            $table->unsignedBigInteger('sticker_id')->unique()->nullable(true); // Unique constraint for one-to-one relationship
            $table->timestamps();
        });

        // Define foreign keys
        Schema::table('images', function (Blueprint $table) {
            $table->foreign('sticker_id')->references('id')->on('stickers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
