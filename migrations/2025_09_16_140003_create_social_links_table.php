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
    Schema::create('social_links', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('platform', 50); // เช่น facebook, twitter, ...
        $table->string('url');          // ลิงก์เต็ม
        $table->string('handle')->nullable(); // @username หรือ custom label
        $table->timestamps();

        // หนึ่ง user ไม่ให้ซ้ำแพลตฟอร์มเดิม (เช่น facebook ได้ 1 รายการ)
        $table->unique(['user_id', 'platform']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
