<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblLearningMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_learning_materials', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->uuid('uuid')->nullable();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('subject')->nullable();
            $table->unsignedBigInteger('grade_level')->nullable();
            $table->string('filename')->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('downloads')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index(['title', 'filename', 'status']);
            $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->foreign('subject')->references('id')->on('tbl_subjects');
            $table->foreign('grade_level')->references('id')->on('tbl_grade_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_learning_materials');
    }
}
