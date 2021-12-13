<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReferral extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $fields)
    {
        $this->fields = $fields;
        $this->emailSubject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = \View::make('emails.new_referral', [
            'subject' => $this->emailSubject,
            'fields' => $this->fields
        ]);
        $html = $view->render();

        return $this
            ->subject($this->emailSubject)
            ->from(config('mail.username'),'Camber Realty')
            ->html($html);
    }
}
