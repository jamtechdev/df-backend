<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id');
            $table->string('language_code', 5);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('overlay_title')->nullable();
            $table->string('overlay_subtitle')->nullable();
            $table->longText('overlay_description')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_translations');
    }
};
