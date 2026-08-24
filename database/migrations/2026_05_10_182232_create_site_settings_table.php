<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->jsonb('meta_title')->default('{}');         // translatable
            $table->jsonb('meta_description')->default('{}');   // translatable
            $table->string('og_image')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->jsonb('social_links')->default('{}');       // {github, linkedin, twitter, ...}
            $table->string('resume_template')->default('default');
            $table->string('contact_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
