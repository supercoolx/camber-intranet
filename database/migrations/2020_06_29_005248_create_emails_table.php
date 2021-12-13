<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event');
            $table->string('description')->nullable();
            $table->text('subject_agent')->nullable();
            $table->longText('body_agent')->nullable();
            $table->text('subject_admin')->nullable();
            $table->longText('body_admin')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
        $insertDefaultPatterns = [
            [
                'event' => 'listing_update',
                'description' => 'Update address listings',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'update_request',
                'description' => 'Update request in dashboard (public and private notes)',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'update_request_status',
                'description' => 'Update t in dashboard',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'create_ad_hoc_request',
                'description' => 'Create AD HOC request on dashboard',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'start_contract',
                'description' => 'Start Contract by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'reserve_conference_room',
                'description' => 'Reserve Conference Room by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'request_social_media_post',
                'description' => 'Request Social Post by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'request_tour',
                'description' => 'Request Tour by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'order_client_gift',
                'description' => 'Order Client Gift by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'buyer_rep_sign',
                'description' => 'Buyer Rep Sign by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ], [
                'event' => 'vendor_list',
                'description' => 'Vendor list by agent',
                'subject_agent' => '',
                'body_agent' => '',
                'subject_admin' => '',
                'body_admin' => '',
            ],
        ];

        foreach($insertDefaultPatterns as $pattern) {
            \App\Email::create($pattern);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
