<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntityIdColumnToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if ( !Schema::hasColumn('roles', 'entity_id')){
        Schema::table('roles', function (Blueprint $table) {
          $table->bigInteger('entity_id')->default(0)->before('created_at');
        });
      }
      if ( !Schema::hasColumn('permissions', 'entity_id')){
        Schema::table('permissions', function (Blueprint $table) {
          $table->bigInteger('entity_id')->default(0)->before('created_at');
        });
      }
      if ( !Schema::hasColumn('role_permissions', 'entity_id')){
        Schema::table('role_permissions', function (Blueprint $table) {
          $table->bigInteger('entity_id')->default(0)->before('created_at');
        });
      }
      if ( !Schema::hasColumn('userroles', 'entity_id')){
        Schema::table('userroles', function (Blueprint $table) {
          $table->bigInteger('entity_id')->default(0)->before('created_at');
        });
      }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      if (Schema::hasColumn('roles', 'entity_id')){
          Schema::table('roles', function (Blueprint $table){
              $table->dropColumn('entity_id');
          });
      }
      if (Schema::hasColumn('permissions', 'entity_id')){
          Schema::table('permissions', function (Blueprint $table){
              $table->dropColumn('entity_id');
          });
      }
      if (Schema::hasColumn('role_permissions', 'entity_id')){
          Schema::table('role_permissions', function (Blueprint $table){
              $table->dropColumn('entity_id');
          });
      }
      if (Schema::hasColumn('userroles', 'entity_id')){
          Schema::table('userroles', function (Blueprint $table){
              $table->dropColumn('entity_id');
          });
      }
    }
}
