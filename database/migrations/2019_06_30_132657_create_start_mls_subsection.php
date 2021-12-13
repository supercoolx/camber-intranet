<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStartMlsSubsection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('subsections', function (Blueprint $table) {
             $table->integer('suborder')->default(10);
        });
        
            $subsections = [
                ['name' => 'Start MLS Listing', 'section_id' => 1, 'order' => 5 ,'suborder'=>20],
            ];
            
            foreach($subsections as $subsection) {
                $subsection = \App\Subsection::create($subsection);
            }

            $fields = [
            [
                'name' => 'When do you need this completed?',
                'placeholder' => 'When do you need this completed?',
                'bottom_text' => '',
                'subsection_id' => $subsection->id,
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
                'subsection_id' =>  $subsection->id,
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
          if (Schema::hasColumn('subsections', 'suborder')) {
            Schema::table('subsections', function (Blueprint $table) {
                $table->dropColumn('suborder');
            });
        }
         $subsection = \App\Subsection::where('name', 'Start MLS Listing')->first()->delete();
         $fields = \App\Field::where('subsection_id', $subsection->id)->delete();
    }
}
