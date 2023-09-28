<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temps', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('governorate');
            $table->string('email');
            $table->string('phone');
            $table->boolean('is_disabled');
            $table->string('collage');
            $table->string('collage_id');
            $table->string('year');
            $table->boolean('is_successded');
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
        Schema::dropIfExists('temps');
    }
}
