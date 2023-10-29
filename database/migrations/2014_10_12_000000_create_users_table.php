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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedMediumInteger('blocked')->default(0);
            $table->dateTime('last_activity')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('skype')->nullable()->unique();
            $table->string('messenger')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('role');
            $table->string('action');
            $table->unsignedBigInteger('city_id');
            $table->index('city_id', 'user_city_idx');
            $table->foreign('city_id', 'user_city_fk')->on('cities')->references('id');
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
