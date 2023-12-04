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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('article_title',225);
            $table->string('article_category',225);
            $table->text('article_content')->nullable();
            $table->string('article_cover',225)->nullable();            
            $table->string('article_status',20);
            $table->timestamps();
            $table->foreignID('author')->constrained(table:'users',indexName:'id')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
