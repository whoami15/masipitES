<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->tinyInteger('role')->default(1);
            $table->string('id_no')->unique()->nullable();
            $table->string('lrn')->unique()->nullable();
            $table->unsignedBigInteger('grade_level')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->text('avatar')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->dateTime('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['username', 'email', 'id_no', 'role']);
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
        Schema::dropIfExists('tbl_users');
    }
}
