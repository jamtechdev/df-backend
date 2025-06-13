<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('national_park_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('national_park_id');
            $table->string('language_code', 5);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->string('lead_quote')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('intro_text_first')->nullable();
            $table->json('hero_image_content')->nullable();
            $table->string('conservation_heading')->nullable();
            $table->longText('conservation_text')->nullable();
            $table->json('park_stats')->nullable();
            $table->string('visuals_title')->nullable();
            $table->string('visuals_subtitle')->nullable();
            $table->string('slug')->unique();
            $table->json('closing_quote')->nullable();
            $table->string('meta_one')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('national_park_id')->references('id')->on('national_parks')->onDelete('cascade');
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_park_translations');
    }
};
