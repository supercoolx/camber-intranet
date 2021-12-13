<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Order;

class OrderSend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $mailMsg)
    {
        $this->order = $order;
        $this->mailFields = $mailMsg;
        $this->emailSubject = 'Listing'. ' (Agent: '.\Auth::user()->name.')';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $view = \View::make('emails.order', [
            'subject' => $this->emailSubject,
            'order' => $this->order,
            'fields' => $this->mailFields
        ]);
        $html = $view->render();
        // or cast the content into a string
        // $html = (string) $view;

        // return $this
        //     ->subject($this->emailSubject)
        //     ->view('emails.order')
        //     ->with([
        //         'order' => $this->order,
        //         'fields' => $this->mailFields,
        //         'subject' => $this->emailSubject,
        //     ]);

        return $this
            ->subject($this->emailSubject)
            ->from(env('MAIL_FROM_ADDRESS'),'Camber Order')
            ->html($html);
    }
}
