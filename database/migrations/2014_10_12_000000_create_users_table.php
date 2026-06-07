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
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->unique();
            $table->integer('height_feet')->nullable();
            $table->integer('height_inches')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->string('category')->nullable(); // BMI category
            $table->string('diet')->nullable(); // veg/non-veg
            $table->string('lifestyle')->nullable(); // active/sedentary etc.
            $table->string('uploaded_image')->nullable(); // user-uploaded photo
            $table->string('ai_image')->nullable(); // AI-generated image path
            $table->string('pdf_report')->nullable(); // generated report filename
            $table->text('diet_plan')->nullable(); // diet plan text or summary
            $table->boolean('whatsapp_sent')->default(false);
            $table->longText('whatsapp_response')->nullable(); // full response body
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
