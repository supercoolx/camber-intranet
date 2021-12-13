<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Subsections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subsections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new subsections with fields';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo 111;
        // $subsections = [
        //     ['name' => 'Add Listing to Camber Website', 'section_id' => 1, 'order' => 12],
        //     ['name' => 'Create Social Media Graphic', 'section_id' => 1, 'order' => 12],
        // ];
        // foreach($subsections as $subsection) {
        //     \App\Subsection::create($subsection);
        // }

        $defaultFields = [
            [
                'name' => 'MLS #',
                'placeholder' => '',
                'bottom_text' => '',
                'subsection_id' => 28,
                'type' => 'textarea',
                'length' => 200,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => '',
                'placeholder' => '',
                'bottom_text' => '',
                'subsection_id' => 29,
                'type' => 'textarea',
                'length' => 200,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],

        ];
        foreach($defaultFields as $field) {
            \App\Field::create($field);
        }
    }
}
