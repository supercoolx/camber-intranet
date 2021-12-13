<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsectionDesignJustSold extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subsections = [
            ['name' => 'Design “Just Sold” postcard', 'section_id' => 3, 'order' => 24, 'suborder' => 10],
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
         $subsection = \App\Subsection::where('name', 'Design “Just Sold” postcard')->first();
         $fields = \App\Field::where('subsection_id', $subsection->id)->delete();
         $subsection->delete();
        
    }
}
