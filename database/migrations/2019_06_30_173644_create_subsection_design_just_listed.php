<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsectionDesignJustListed extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        $subsections = [
            ['name' => 'Design "Just Listed" postcard', 'section_id' => 1, 'order' => 11, 'suborder' => 20],
        ];

        foreach ($subsections as $subsection) {
            $subsection = \App\Subsection::create($subsection);
        }

        $fields = [
            [
                'name'          => 'When do you need this completed?',
                'placeholder'   => 'When do you need this completed?',
                'bottom_text'   => '',
                'subsection_id' => $subsection->id,
                'type'          => 'text',
                'length'        => 100,
                'options'       => null,
                'required'      => 0,
                'order'         => 1
            ],
             [
                'name'          => 'What area / radius / neighborhood do you want to mail to?',
                'placeholder'   => 'What area / radius / neighborhood do you want to mail to?',
                'bottom_text'   => '',
                'subsection_id' => $subsection->id,
                'type'          => 'text',
                'length'        => 200,
                'options'       => null,
                'required'      => 0,
                'order'         => 2
            ],
             [
                'name'          => 'How many do you want to mail?',
                'placeholder'   => 'How many do you want to mail?',
                'bottom_text'   => '',
                'subsection_id' => $subsection->id,
                'type'          => 'text',
                'length'        => 100,
                'options'       => null,
                'required'      => 0,
                'order'         => 3
            ],
            [
                'name'          => 'Enter any additional details for your assistant',
                'placeholder'   => 'Enter any additional details for your assistant',
                'bottom_text'   => '',
                'subsection_id' => $subsection->id,
                'type'          => 'text',
                'length'        => 1000,
                'options'       => null,
                'required'      => 0,
                'order'         => 4
            ],
        ];

        foreach ($fields as $field) {
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

         $subsection = \App\Subsection::where('name', 'Design "Just Listed" postcard')->first();
         $subsection->delete();
         $fields = \App\Field::where('subsection_id', $subsection->id)->delete();
    }

}
