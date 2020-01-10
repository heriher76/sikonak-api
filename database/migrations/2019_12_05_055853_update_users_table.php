<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->string('hp')->after('password')->nullable();
          $table->string('address')->after('hp')->nullable();
          $table->string('status')->after('address')->nullable();
          $table->string('photo')->after('status')->nullable();
          $table->integer('id_family')->after('photo')->unsigned()->nullable();
          $table->foreign('id_family')->references('id')->on('families')->onUpdate('cascade')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('hp');
        $table->dropColumn('address');
        $table->dropColumn('status');
        $table->dropColumn('photo');
        $table->dropColumn('id_family');
      });
    }
}
