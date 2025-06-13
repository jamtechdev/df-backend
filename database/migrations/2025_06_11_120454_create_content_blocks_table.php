<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(np_table('content_blocks'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('national_park_translation_id');
            $table->enum('section_type', ['key_feature', 'explore', 'other', 'journey']);
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('national_park_translation_id')
                ->references('id')
                ->on('national_park_translations')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(np_table('content_blocks'));
    }
};
