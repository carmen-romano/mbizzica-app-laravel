<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasteTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paste_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('paste_id');
            $table->unsignedBiginteger('tag_id');
            $table->foreign('paste_id')->references('id')
                ->on('pastes')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')
                ->on('tags')->onDelete('cascade');
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
        Schema::dropIfExists('paste_tags');
    }
}
