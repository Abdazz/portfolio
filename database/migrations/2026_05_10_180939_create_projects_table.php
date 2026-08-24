<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->jsonb('title')->default('{}');
            $table->jsonb('slug')->default('{}');
            $table->jsonb('summary')->default('{}');
            $table->jsonb('body')->default('{}');
            $table->jsonb('tech_stack')->default('[]');
            $table->jsonb('links')->default('[]');
            $table->boolean('featured')->default(false)->index();
            $table->unsignedInteger('order')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE INDEX projects_title_gin ON projects USING gin (title jsonb_path_ops)');
            DB::statement('CREATE INDEX projects_summary_gin ON projects USING gin (summary jsonb_path_ops)');
            DB::statement('CREATE INDEX projects_body_gin ON projects USING gin (body jsonb_path_ops)');

            // Per-locale unique slug expression indexes
            DB::statement("CREATE UNIQUE INDEX projects_slug_en_unique ON projects ((slug->>'en')) WHERE (slug->>'en') IS NOT NULL AND (slug->>'en') != ''");
            DB::statement("CREATE UNIQUE INDEX projects_slug_fr_unique ON projects ((slug->>'fr')) WHERE (slug->>'fr') IS NOT NULL AND (slug->>'fr') != ''");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
