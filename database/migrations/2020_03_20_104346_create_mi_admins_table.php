<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mi_admins', function (Blueprint $table) {
            $table->id();
            $table->string('admin_name', 100);
            $table->string('admin_email', 200);
            $table->text('password');
            $table->string('admin_phone', 11);
            $table->tinyInteger('role')->default(2)->comment('1=Admin;2=Manager');
            $table->tinyInteger('status')->default(1)->comment('1=active;2=inactive');
            $table->rememberToken();
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
        Schema::dropIfExists('mi_admins');
    }
}
