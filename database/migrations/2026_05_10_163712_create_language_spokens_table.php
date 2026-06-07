<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages_spoken', function (Blueprint $table) {
            $table->id();
            $table->jsonb('name')->default('{}');
            $table->string('level');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages_spoken');
    }
};
