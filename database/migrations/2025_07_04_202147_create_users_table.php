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
            $table->string("password");
            $table->string("token", 100)->nullable();
            $table->dateTime("email_verified_at")->nullable()->default(null);
            $table->dateTime("last_login_at")->nullable()->default(null);
            $table->boolean("active")->nullable()->default(null);
            $table->dateTime("blocked_until")->nullable()->default(null);
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
