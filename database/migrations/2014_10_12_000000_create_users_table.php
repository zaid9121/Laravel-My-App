<?php

use Illuminate\Database\Migrations\Migration; #Migration class is the base class for all Laravel database migrations. 
use Illuminate\Database\Schema\Blueprint; #Blueprint class is used to define the structure of database tables in Laravel migrations.
use Illuminate\Support\Facades\Schema; # Schema facade provides a convenient way to interact with the database schema in Laravel.

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('avatar')->nullable(); #nullable() method -> column is allowed to have a NULL value
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
