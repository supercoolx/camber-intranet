<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStartMls extends Migration
{
/**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //removing
        $res = \App\Field::where('subsection_id',24)->delete();

         $fields = [
            [
                'name' => 'When do you need this completed?',
                'placeholder' => 'When do you need this completed?',
                'bottom_text' => '',
                'subsection_id' => 24,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Enter any additional details for your assistant',
                'placeholder' => 'Enter any additional details for your assistant',
                'bottom_text' => 'It’s always the agent’s responsibility to carefully review the MLS entry before the
status is changed from incoming to active.',
                'subsection_id' => 24,
                'type' => 'text',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
                ];

             foreach($fields as $field) {
                \App\Field::create($field);
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         $res = \App\Field::where('subsection_id',24)->delete();
           $fields = [
            [
                'name' => 'When do you need this completed?',
                'placeholder' => 'When do you need this completed?',
                'bottom_text' => '',
                'subsection_id' => 24,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Enter any additional details for your assistant',
                'placeholder' => 'Enter any additional details for your assistant',
                'bottom_text' => '',
                'subsection_id' => 24,
                'type' => 'text',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
                ];

             foreach($fields as $field) {
                \App\Field::create($field);
            }
    }
}
