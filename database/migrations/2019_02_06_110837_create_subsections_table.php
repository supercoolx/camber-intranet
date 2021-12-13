<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsections', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('subheader')->nullable();
            $table->integer('section_id');
            $table->integer('order');
            $table->timestamps();
        });

        //DB::table('subsections')->insert([
        $insertDefaultSections = [
            ['name' => 'Seller Info and Price', 'section_id' => 1, 'order' => 1],
            ['name' => 'Start CMA in Matrix', 'section_id' => 1, 'order' => 2],
            ['name' => 'Order O&E from title', 'section_id' => 1, 'order' => 3],
            ['name' => 'Order Square Footage Measurements / Floor Plan', 'section_id' => 1, 'order' => 4],
            ['name' => 'Start Listing Contract and Disclosures in CTM', 'section_id' => 1, 'order' => 5],
            ['name' => 'Schedule Staging', 'section_id' => 1, 'order' => 6],
            ['name' => 'Purchase Website Domain Name', 'section_id' => 1, 'order' => 7],
            ['name' => 'Order Custom Sign Rider', 'section_id' => 1, 'order' => 8],
            ['name' => 'Order Photography', 'section_id' => 1, 'order' => 10],
            ['name' => 'Design Brochure', 'section_id' => 1, 'order' => 11],
            ['name' => 'Design MediaMax Website', 'section_id' => 1, 'order' => 12],
            ['name' => 'Order Interior Signage', 'section_id' => 1, 'order' => 13],
            ['name' => 'Order Yard Sign Installation', 'section_id' => 1, 'order' => 9],
            ['name' => 'Install Custom Sign Rider', 'section_id' => 1, 'order' => 14],
            ['name' => 'Additional Comments', 'section_id' => 1, 'order' => 15],
            [
                'name' => 'Design Email Announcement',
                'subheader' => 'Your assistant will use information from your brochure unless otherwise instructed',
                'section_id' => 1,
                'order' => 16
            ],
            // ['name' => 'Design Email Flyer Announcement', 'section_id' => 1, 'order' => 17],
            ['name' => 'Input Showing Instructions in CSS', 'section_id' => 1, 'order' => 18],
            ['name' => 'Input Open House Details in MLS', 'section_id' => 1, 'order' => 19],
            ['name' => 'Change MLS Status from Active to Under Contract', 'section_id' => 2, 'order' => 20],
            ['name' => 'Change Yard Sign rider to “Under Contract” rider', 'section_id' => 2, 'order' => 21],
            ['name' => 'Change Yard Sign rider to “Sold” rider', 'section_id' => 3, 'order' => 22],
            ['name' => 'Remove Yard Sign', 'section_id' => 3, 'order' => 23]
        ];

        foreach($insertDefaultSections as $subsection) {
            \App\Subsection::create($subsection);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subsections');
    }
}
