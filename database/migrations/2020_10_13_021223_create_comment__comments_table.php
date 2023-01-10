<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment__comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('title')->nullable();
            $table->text('content');
            $table->unsignedBigInteger('page_id')->nullable();
            $table->boolean('is_from_admin')->default(true);
            $table->boolean('is_show_frontend')->default(false);
            $table->nestedSet();
            $table->integer('like')->default(0);
            $table->integer('dislike')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->string('customer_token')->nullable();

            $table->nullableMorphs('table');

            $table->foreign('page_id')->references('id')->on('comment__pages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment__comments');
    }
}
