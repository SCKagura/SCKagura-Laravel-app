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
    Schema::create('user_bios', function (Blueprint $table) {
        $table->id(); // Auto-incrementing primary key
        $table->unsignedBigInteger('user_id')->unique(); // Foreign key to the users table where unsignedBigInteger is used to store only positive integers
        $table->text('bio')->nullable();
        $table->timestamps(); // Adds created_at and updated_at columns
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bios');
    }
};
