<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->string('slug')->unique();
            $table->string('image_name');
            $table->foreign('publisher_id')->references('id')->on('publishers');
            $table->foreign('authors_id')->references('id')->on('authors');
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['authors_id']);
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
        Schema::dropIfExists('books');
    }
}
