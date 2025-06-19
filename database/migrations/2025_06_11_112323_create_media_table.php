<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Polymorphic relation
            $table->morphs('mediable'); // this will create `mediable_id` and `mediable_type`

            $table->string('type')->nullable(); // hero_image, gallery_image, etc.
            $table->string('s3_bucket')->nullable();
            $table->string('s3_url')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->json('dimensions')->nullable(); // width, height, thumbnails
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable(); // EXIF, copyright etc.
            $table->boolean('is_gallery_visual')->default(false);
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
