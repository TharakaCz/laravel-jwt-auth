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
            $table->id('id');
            $table->uuid('uuid')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->char('phone')->unique()->nullable();
            $table->char('lan_phone')->unique()->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('avatar_type')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('token')->nullable();
            $table->string('google_id')->unique()->nullable();
            $table->string('facebook_id')->unique()->nullable();
            $table->string('apple_id')->unique()->nullable();
            $table->string('microsoft_id')->unique()->nullable();
            $table->char('nic')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->integer('is_active')->default(1);
            $table->integer('attempts')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
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
