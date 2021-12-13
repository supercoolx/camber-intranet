<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->integer('order');
            $table->timestamps();
        });

        DB::table('sections')->insert([
            ['name' => 'New Listing','order' => 1],
            ['name' => 'Under Contract','order' => 2],
            ['name' => 'Sold','order' => 3]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
