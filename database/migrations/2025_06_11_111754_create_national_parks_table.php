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
        Schema::create('national_parks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
             $table->string('name', 200);
            $table->string('slug')->unique();
            $table->unsignedBigInteger('theme_id')->nullable();

            // SEO Columns
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable(); // comma separated keywords

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_parks');
    }
};
