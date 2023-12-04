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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('ori_comment');
            $table->text('mod_comment');
            $table->string('comment_status',20);
            $table->timestamps();
            $table->foreignID('comment_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignID('blog_id')->constrained('blog_posts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
