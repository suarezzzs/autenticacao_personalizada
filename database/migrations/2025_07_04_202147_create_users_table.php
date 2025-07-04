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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("username", 30)->unique();
            $table->string("email", 50)->unique();
            $table->string("password", 32);
            $table->string("token", 100)->nullable();
            $table->dateTime("email_verified_at")->nullable()->defalt(null);
            $table->dateTime("last_login_at")->nullable()->defalt(null);
            $table->boolean("active")->nullable()->defalt(null);
            $table->dateTime("blocked_until")->nullable()->defalt(null);
            $table->timestamps();
            $table->softDeletes();
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
