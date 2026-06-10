<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Laravel default
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Custom fields
            $table->string('avatar')->nullable()->comment('Avatar URL');
            $table->string('phone')->nullable();

            $table->enum('role', [
                'user',
                'seller',
                'root'
            ])->default('user')->comment('User role');

            $table->enum('current_role', [
                'user',
                'seller',
                'root'
            ])->nullable()->comment('Active role when seller switches');

            $table->string('referral_code')
                ->unique()
                ->nullable()
                ->comment('Unique referral code for sellers');

            $table->foreignId('referred_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('User who referred this user');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('role');
            $table->index('current_role');
            $table->index('is_active');
        });

        // Laravel default
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};