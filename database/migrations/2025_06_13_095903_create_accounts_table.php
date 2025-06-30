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
    {if (!Schema::hasTable('accounts')) {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('full_name', 225);
            $table->string('avatar', 225)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('email', 225)->unique();
            $table->string('phone', 10)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('password', 225);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
