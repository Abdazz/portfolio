<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->jsonb('title')->default('{}');
            $table->string('company');
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->jsonb('description')->default('{}');
            $table->unsignedInteger('order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE INDEX experiences_title_gin ON experiences USING gin (title jsonb_path_ops)');
            DB::statement('CREATE INDEX experiences_description_gin ON experiences USING gin (description jsonb_path_ops)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
