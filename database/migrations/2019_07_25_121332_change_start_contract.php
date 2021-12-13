<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStartContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //removing
        $res = \App\Field::where('subsection_id',5)->delete();

           $fields = [
            [
                'name' => 'When do you need this completed?',
                'placeholder' => 'When do you need this completed?',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
              [
                'name' => 'Which Title Company and Closer will you be using?',
                'placeholder' => 'Which Title Company and Closer will you be using?',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
                 [
                'name' => 'What is the list price?',
                'placeholder' => 'What is the list price?',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
                 [
                'name' => 'How much is Earnest Money?',
                'placeholder' => 'How much is Earnest Money?',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 4
            ],

                 [
                'name' => 'How much are the HOA fees? Frequency?',
                'placeholder' => 'How much are the HOA fees? Frequency?',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 5
            ],



            [
                'name' => 'Enter any additional details for your assistant',
                'placeholder' => 'Enter any additional details for your assistant',
                'bottom_text' => 'It’s always the agent’s responsibility to carefully review the listing contract and
disclosures before sending to their client.',
                'subsection_id' =>  5,
                'type' => 'text',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 6
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
         $res = \App\Field::where('subsection_id',5)->delete();
         $fields = [
            [
                'name' => 'When do you need this completed?',
                'placeholder' => 'When do you need this completed?',
                'bottom_text' => '',
                'subsection_id' => 5,
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
                'subsection_id' =>  5,
                'type' => 'text',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 6
            ],
                ];

             foreach($fields as $field) {
                \App\Field::create($field);
            }
    }
}
