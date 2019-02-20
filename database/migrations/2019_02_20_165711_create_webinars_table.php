<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebinarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webinars', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('label');
            $table->string('slug')->unique()->index();

            $table->unsignedInteger('provider_id');

            $table->text('description');
            $table->text('content');

            $table->timestamps();

            $table->foreign('provider_id')
                ->references('id')->on('providers')
                ->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('webinars');
    }
}
