<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * User id is unique UUID indetifier which is implemented as a trait that can be reused across models: app/Traits/UUID.php
     * User has option to pick and save countrry, language and category.
     * Country, language and category will be used to filter all the news.
     * Column role (boolean type) will determine if user is guest or admin (0=guest, 1=admin).
     * Admin user will have permission to access admin backoffice.
     */

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('country');
            $table->string('language');
            $table->string('category');
            $table->boolean('role');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

