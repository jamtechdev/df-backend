<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(np_table('hidden_wonders'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('national_park_translation_id');
            $table->string('section_heading')->nullable();
            $table->string('section_title');
            $table->string('section_subtitle')->nullable();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('description');
            $table->string('tip_heading')->nullable();
            $table->longText('tip_text')->nullable();
            $table->longText('quote')->nullable();
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
        Schema::dropIfExists(np_table('hidden_wonders'));
    }
};
