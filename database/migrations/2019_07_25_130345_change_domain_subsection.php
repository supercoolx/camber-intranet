<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDomainSubsection extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subsection = \App\Subsection::where('name', 'Purchase Website Domain Name')->first();
        $subsection->name = 'Request Website Domain Name';
        $subsection->save();

        $res = \App\Field::where('subsection_id', 7)->delete();
        $fields = [[
        'name'          => 'Enter domain name options your assistant should search for',
        'placeholder'   => 'Enter domain name options your assistant should search for',
        'bottom_text'   => '',
        'subsection_id' => 7,
        'type'          => 'text',
        'length'        => 100,
        'options'       => null,
        'required'      => 0,
        'order'         => 1
            ],
            [
                'name'          => 'Check Availability',
                'placeholder'   => 'https://www.whois.net/',
                'bottom_text'   => '',
                'subsection_id' => 7,
                'type'          => 'button',
                'length'        => 100,
                'options'       => null,
                'required'      => 0,
                'order'         => 2
            ],
            [
                'name'          => 'Available Camber Domains',
                'placeholder'   => 'https://docs.google.com/spreadsheets/d/1AOyJu33-lP3fedtdaTOfRSgTfakVbTwLIkHNV1WEpnU/edit#gid=0',
                'bottom_text'   => '',
                'subsection_id' => 7,
                'type'          => 'button',
                'length'        => 100,
                'options'       => null,
                'required'      => 0,
                'order'         => 3
            ],
              [
                'name' => '',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'This is Camber owned;Purchase domain',
                'subsection_id' => 7,
                'type' => 'radio',
                'length' => 0,
                'required' => 1,
                'order' => 4
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
        $subsection = \App\Subsection::where('name', 'Request Website Domain Name')->first();
        $subsection->name = 'Purchase Website Domain Name';
        $subsection->save();

        $res = \App\Field::where('subsection_id', 7)->delete();
        $fields = [

               [
                'name'          => 'Available Camber Domains',
                'placeholder'   => 'https://docs.google.com/spreadsheets/d/1AOyJu33-lP3fedtdaTOfRSgTfakVbTwLIkHNV1WEpnU/edit#gid=0',
                'bottom_text'   => '',
                'subsection_id' => 7,
                'type'          => 'button',
                'length'        => 100,
                'options'       => null,
                'required'      => 0,
                'order'         => 1
            ],
            [
                'name'          => 'Check Availability',
                'placeholder'   => 'https://www.whois.net/',
                'bottom_text'   => '',
                'subsection_id' => 7,
                'type'          => 'button',
                'length'        => 100,
                'options'       => null,
                'required'      => 0,
                'order'         => 2
            ],
            [
        'name'          => 'Enter domain name options your assistant should search for',
        'placeholder'   => 'Enter domain name options your assistant should search for',
        'bottom_text'   => '',
        'subsection_id' => 7,
        'type'          => 'text',
        'length'        => 100,
        'options'       => null,
        'required'      => 0,
        'order'         => 3
            ],

            
        ];
        foreach ($fields as $field) {
            \App\Field::create($field);
        }
    }

}
