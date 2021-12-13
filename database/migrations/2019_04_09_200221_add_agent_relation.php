<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgentRelation extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('orders', function (Blueprint $table) {
            $table->integer('agent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         if (Schema::hasColumn('orders', 'agent_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('agent_id');
            });
        }
    }
}
