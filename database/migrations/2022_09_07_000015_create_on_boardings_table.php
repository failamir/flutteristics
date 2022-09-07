<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnBoardingsTable extends Migration
{
    public function up()
    {
        Schema::create('on_boardings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('spotlight')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
