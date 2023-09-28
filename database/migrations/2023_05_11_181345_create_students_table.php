<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('governorate');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
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
        Schema::dropIfExists('students');
    }
}
