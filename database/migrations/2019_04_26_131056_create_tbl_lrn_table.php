<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblLrnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_lrn', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('used_by_user_id')->nullable();
            $table->string('lrn')->unique()->nullable();
            $table->enum('status', ['USED', 'UNUSED'])->default('UNUSED');
            $table->dateTime('used_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->foreign('used_by_user_id')->references('id')->on('tbl_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_lrn');
    }
}
