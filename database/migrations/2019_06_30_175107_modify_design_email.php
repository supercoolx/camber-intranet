<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDesignEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $subsection = \App\Subsection::where('name', 'Design Email Announcement')->first();
       $subsection->name = 'Design Email Announcement (eBlast)';
       $subsection->save();

       $field = \App\Field::where('name', 'When you need the proof sent to you?')->first();
       $field->name = 'When do you need the proof sent to you?';
       $field->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $field = \App\Field::where('name', 'When do you need the proof sent to you?')->first();
       $field->name = 'When you need the proof sent to you?';
       $field->save();

       $subsection = \App\Subsection::where('name', 'Design Email Announcement (eBlast)')->first();
       $subsection->name = 'Design Email Announcement';
       $subsection->save();

    }
}
