<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDesignBrochure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $field = \App\Field::where('name', 'Template Name')->first();
       $field->options = 'Double sided ﬂyer <a class="help" href="#help1">example</a>;
           Square 4-page brochure <a class="help" href="#help2">example</a>;
           Horizontal 4-page brochure <a class="help" href="#help3">example</a>;
           Vertical 4-page brochure <a class="help" href="#help4">example</a>;
           Custom';
       $field->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $field = \App\Field::where('name', 'Template Name')->first();
       $field->options = 'Double sided ﬂyer ;
           Square 4-page brochure ;
           Horizontal 4-page brochure ;
           Vertical 4-page brochure ;
           Custom';
       $field->save();
    }
}
