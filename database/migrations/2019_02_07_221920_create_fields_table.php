<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('');
            $table->text('placeholder')->nullable();
            $table->text('bottom_text')->nullable();

            $table->integer('subsection_id')->unsigned()->index();
            // $table->foreign('subsection_id')
            //     ->references('id')
            //     ->on('subsections')
            //     ->onDelete('cascade');

            $table->string('type')->default('text');
            $table->integer('length')->nullable();
            $table->text('options')->nullable();
            $table->text('default')->nullable();

            // $table->boolean('process')->default(false);
            $table->boolean('required')->default(false);
            $table->integer('order');
            $table->timestamps();
        });

        // DB::table('fields')->insert([
        $defaultFields = [
            [
                'name' => 'Price',
                'placeholder' => 'price',
                'bottom_text' => '',
                'subsection_id' => 1,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Neighborhood name',
                'placeholder' => 'Neighborhood name',
                'bottom_text' => '',
                'subsection_id' => 1,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'Sellers Names',
                'placeholder' => 'Sellers Name',
                'bottom_text' => '',
                'subsection_id' => 1,
                'type' => 'text',
                'length' => 70,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            [
                'name' => 'Seller phone numbers',
                'placeholder' => 'Seller phone numbers',
                'bottom_text' => '',
                'subsection_id' => 1,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 4
            ],
            [
                'name' => 'Seller email addresses',
                'placeholder' => 'Email',
                'bottom_text' => '',
                'subsection_id' => 1,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 5
            ],
            //subsection 2
            [
                'name' => 'Enter MLS numbers, cart name, or other comp details',
                'placeholder' => 'Enter MLS numbers, cart name, or other comp details',
                'bottom_text' => '',
                'subsection_id' => 2,
                'type' => 'text',
                'length' => 200,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //subsection 3 Order O&E from title
            [
                'name' => 'Enter name of title company and contact person',
                'placeholder' => 'Enter name of title company and contact person',
                'bottom_text' => '',
                'subsection_id' => 3,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //subsection 4 Order Square Footage Measurements / Floor Plan
            [
                'name' => 'Enter contact info for the appraiser/measurement company',
                'placeholder' => 'Enter contact info for the appraiser/measurement company',
                'bottom_text' => '',
                'subsection_id' => 4,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Enter preferred date(s) and time(s) for the measurement appointment',
                'placeholder' => 'Enter preferred date(s) and time(s) for the measurement appointment',
                'bottom_text' => '',
                'subsection_id' => 4,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'How will the appraiser / measurement company access the property?',
                'placeholder' => 'How will the appraiser / measurement company access the property?',
                'bottom_text' => '',
                'subsection_id' => 4,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            //subsection 5 Start Listing Contract and Disclosures in CTM
            [
                'name' => 'When do you need this completed?',
                'placeholder' => 'When do you need this completed?',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 200,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Enter any additional details for your assistant',
                'placeholder' => 'Enter any additional details for your assistant',
                'bottom_text' => '',
                'subsection_id' => 5,
                'type' => 'text',
                'length' => 500,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            //subsection 6 Schedule Staging
            [
                'name' => 'Enter contact info for the stager',
                'placeholder' => 'Enter contact info for the stager',
                'bottom_text' => '',
                'subsection_id' => 6,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => ' Enter preferred date(s) and time(s) for the appointment',
                'placeholder' => 'Date',
                'bottom_text' => '',
                'subsection_id' => 6,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'How will the stager access the property?',
                'placeholder' => 'How will the stager access the property?',
                'bottom_text' => '',
                'subsection_id' => 6,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            //subsection 7 Purchase Website Domain Name
            [
                'name' => 'Enter domain name options your assistant should search for',
                'placeholder' => 'Enter domain name options your assistant should search for',
                'bottom_text' => '',
                'subsection_id' => 7,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Check Availability',
                'placeholder' => 'https://www.whois.net/',
                'bottom_text' => '',
                'subsection_id' => 7,
                'type' => 'button',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'Available Camber Domains',
                'placeholder' => 'https://docs.google.com/spreadsheets/d/1AOyJu33-lP3fedtdaTOfRSgTfakVbTwLIkHNV1WEpnU/edit#gid=0',
                'bottom_text' => '',
                'subsection_id' => 7,
                'type' => 'button',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            //subsection 8 Order Custom Sign Rider Order
            [
                'name' => 'Enter text for the sign rider',
                'placeholder' => 'Enter text for the sign rider',
                'bottom_text' => 'RMD needs at least 3 full business days to create the rider and move it to their inventory',
                'subsection_id' => 8,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => '',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Agent to pick-up rider at RMD;Place rider in RMD inventory for installation',
                'subsection_id' => 8,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 2
            ],
            //subsection 9 Order Photography
            [
                'name' => 'Enter photography company and preferred photographer name',
                'placeholder' => 'Enter photography company and preferred photographer name',
                'bottom_text' => '',
                'subsection_id' => 9,
                'type' => 'text',
                'length' => 70,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Photography package type',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => '15 photos;20 photos;25 photos;30 photos;35 photos',
                'default' => '35 photos',
                'subsection_id' => 9,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'Twilight photos needed?',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'default' => 'No',
                'subsection_id' => 9,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 3
            ],
            [
                'name' => 'Aerial photos needed?',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'default' => 'No',
                'subsection_id' => 9,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 4
            ],
            [
                'name' => 'Matterport 3D tour needed?',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'default' => 'No',
                'subsection_id' => 9,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 5
            ],
            [
                'name' => 'Video production needed?',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'default' => 'No',
                'subsection_id' => 9,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 6
            ],
            [
                'name' => 'Enter preferred date(s) and time(s) for the photography appointment',
                'placeholder' => 'Date',
                'bottom_text' => '',
                'subsection_id' => 9,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 7
            ],
            [
                'name' => 'How will the photographer access the property?',
                'placeholder' => 'How will the photographer access the property?',
                'bottom_text' => '',
                'subsection_id' => 9,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 8
            ],
            [
                'name' => 'Additional Information',
                'placeholder' => 'Additional Information',
                'bottom_text' => '',
                'subsection_id' => 9,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 9
            ],
            //subsection 10 Design Brochure
            [
                'name' => 'Template name',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => ' Double sided ﬂyer;Square 4-page brochure;Horizontal 4-page brochure;Vertical 4-page brochure;Custom',
                'subsection_id' => 10,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Property Overview - price, bedrooms, bathrooms, sq ft, lot size, etc.',
                'placeholder' => 'Property Overview - price, bedrooms, bathrooms, sq ft, lot size, etc.',
                'bottom_text' => '',
                'subsection_id' => 10,
                'type' => 'textarea',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'Special Features - listed as bullet points',
                'placeholder' => 'Special Features - listed as bullet points',
                'bottom_text' => '',
                'subsection_id' => 10,
                'type' => 'textarea',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            [
                'name' => 'Descriptive Paragraph',
                'placeholder' => 'Description',
                'bottom_text' => '',
                'subsection_id' => 10,
                'type' => 'textarea',
                'length' => 1500,
                'options' => null,
                'required' => 0,
                'order' => 4
            ],
            //subsection 11 Design MediaMax Website
            [
                'name' => 'If different copy is needed please enter it here',
                'placeholder' => 'Broshure copy for the website',
                'bottom_text' => '',
                'subsection_id' => 11,
                'type' => 'textarea',
                'length' => 1000,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //subsection 12 Order Interior Signage
            [
                'name' => 'Enter copy for the signage',
                'placeholder' => 'Enter copy for the signage',
                'bottom_text' => '',
                'subsection_id' => 12,
                'type' => 'textarea',
                'length' => 500,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //subsection 13 Order Yard Sign Installation
            [
                'name' => 'Enter installation date, note that RMD requires at least 2 business days advance notice',
                'placeholder' => 'Enter installation date',
                'bottom_text' => '',
                'subsection_id' => 13,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Do you want a "Coming Soon" rider placed at the time of installation?',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'subsection_id' => 13,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 2
            ],
            //subsection 14 Install Custom Sign Rider
            [
                'name' => 'Enter date for RMD to install the custom rider, note that RMD requires at least 2 business days advance notice',
                'placeholder' => 'Enter date for RMD to install the custom rider, note that RMD requires at least 2 business days advance notice',
                'bottom_text' => '',
                'subsection_id' => 14,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => ' Is RMD removing a "Coming Soon" rider at the time of installing the custom rider?',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'subsection_id' => 14,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 2
            ],
            //subsection 15 Additional Comments
            [
                'name' => 'Enter information sources where the assistant can pull data for MLS - Realist Tax record, past MLS listing, Listing Contract, etc.',
                'placeholder' => 'Enter information sources where the assistant can pull data for MLS - Realist Tax record, past MLS listing, Listing Contract, etc.',
                'bottom_text' => 'It’s always the agent’s responsibility to carefully review the MLS entry before the status is changed from Incoming to Active',
                'subsection_id' => 15,
                'type' => 'textarea',
                'length' => 200,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'When do you need this completed',
                'placeholder' => 'When do you need this completed',
                'bottom_text' => '',
                'subsection_id' => 15,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //subsection 16 Design Email Announcement
            [
                'name' => 'Additional details',
                'placeholder' => 'Additional details',
                'bottom_text' => 'Note: If this is a broker open announcement, enter the date and time of the event',
                'subsection_id' => 16,
                'type' => 'textarea',
                'length' => 700,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Enter the date(s) you want the Broker Open Announcement emailed',
                'placeholder' => 'Date',
                'bottom_text' => '',
                'subsection_id' => 16,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'When you need the proof sent to you?',
                'placeholder' => 'When you need the proof sent to you?',
                'bottom_text' => 'No emails will be sent unless you approve the proof. You are responsible for carefully reviewing the proof',
                'subsection_id' => 16,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            //subsection 17 Input Showing Instructions in CSS
            [
                'name' => 'Enter Seller contact methods: phone, email and/or text',
                'placeholder' => 'Enter Seller contact methods: phone, email and/or text',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Enter showing type: Go and show, Courtesy call to seller, must obtain seller approval, etc.',
                'placeholder' => 'Enter showing type: Go and show, Courtesy call to seller, must obtain seller approval, etc.',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'Enter lock box location',
                'placeholder' => 'Enter lock box location',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 3
            ],
            [
                'name' => 'Enter lock box code',
                'placeholder' => 'Enter lock box code',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 4
            ],
            [
                'name' => 'Enter garage code',
                'placeholder' => 'Enter garage code',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 5
            ],
            [
                'name' => 'Enter alarm code',
                'placeholder' => 'Enter alarm code',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 6
            ],
            [
                'name' => 'Are there pets at the property?  If so, are there special instructions? ',
                'placeholder' => 'Are there pets at the property?  If so, are there special instructions? ',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 7
            ],
            [
                'name' => 'Maximum appointment length',
                'placeholder' => 'Maximum appointment length',
                'bottom_text' => '',
                'subsection_id' => 18,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 8
            ],
            [
                'name' => 'Minimum showing notice to seller',
                'placeholder' => 'Minimum showing notice to seller',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 9
            ],
            [
                'name' => 'Instructions for showing agents',
                'placeholder' => 'Instructions for showing agents',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 10
            ],
            [
                'name' => 'Instructions for CSS',
                'placeholder' => 'Instructions for CSS',
                'bottom_text' => '',
                'subsection_id' => 17,
                'type' => 'text',
                'length' => 100,
                'options' => null,
                'required' => 0,
                'order' => 11
            ],
            //subsection 18 Input Open House Details in MLS
            [
                'name' => 'Enter open house date(s), start time, end time',
                'placeholder' => 'Enter open house date(s), start time, end time',
                'bottom_text' => '',
                'subsection_id' => 18,
                'type' => 'text',
                'length' => 70,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //UNDER CONTRACT
            //20.Change MLS Status from Active to Under Contract
            [
                'name' => 'Under Contract Date',
                'placeholder' => 'Under Contract Date',
                'bottom_text' => '',
                'subsection_id' => 19,
                'type' => 'text',
                'length' => 200,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            [
                'name' => 'Status Conditions',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'None Known;Court;Equitable;Interest;Short Sale',
                'subsection_id' => 19,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 2
            ],
            [
                'name' => 'Contingent Approval Conditions',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'None Known;Government Approval;Kick Out - contingent on home sale;Offer accepted waiting on REO approval;Offer accepted contingent upon court approval;Offer waiting on RELO company approval;Short sale - have signed offer(s) waiting on lender approval;Short sale - have unsigned offer(s) waiting on lender approval',
                'subsection_id' => 19,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 3
            ],
            [
                'name' => 'Accepting Backup Offers',
                'placeholder' => '',
                'bottom_text' => '',
                'options' => 'Yes;No',
                'subsection_id' => 19,
                'type' => 'radio',
                'length' => 0,
                'required' => 0,
                'order' => 4
            ],
            //21.Change Yard Sign rider to "Under Contract" rider
            [
                'name' => 'Enter date for RMD to change out existing rider with "Under Contract" rider.',
                'placeholder' => 'If yes button is selected',
                'bottom_text' => 'Note that RMD requires at least 2 business days advance notice',
                'subsection_id' => 20,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //SOLD SECTION
            //22.Change Yard Sign rider to "Sold" rider
            [
                'name' => 'Enter date for RMD to change out existing rider with "Sold" rider',
                'placeholder' => 'Enter date for RMD to change out existing rider with "Sold" rider',
                'bottom_text' => 'Note that RMD requires at least 2 business days advance notice',
                'subsection_id' => 21,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],
            //23.Remove Yard Sign
            [
                'name' => 'Enter date for RMD to remove the yard sign',
                'placeholder' => 'Enter date for RMD to remove the yard sign',
                'bottom_text' => 'Note that RMD requires at least 2 business days advance notice',
                'subsection_id' => 22,
                'type' => 'text',
                'length' => 50,
                'options' => null,
                'required' => 0,
                'order' => 1
            ],

        ];

        foreach($defaultFields as $field) {
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
        Schema::dropIfExists('fields');
    }
}
