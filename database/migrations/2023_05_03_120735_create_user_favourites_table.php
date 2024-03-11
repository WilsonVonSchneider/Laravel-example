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
        Schema::create('user_favourites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('userId')->onDelete('cascade');
            $table->string('title');
            $table->text('url');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->text('imageUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favourites');
    }
};
