<?php

namespace App\Http\Controllers;

use App\Email;
use App\Facades\EmailPattern;
use App\Helpers\HelperString;
use App\OrderRequest;
use Illuminate\Http\Request;
use App\Order;
use App\User;
use App\Mail\AgentCommon;
use Illuminate\Support\Facades\Mail;
use Session;

class MailController extends Controller
{
    private $orderRequest;
    private $global_data_pattern;

    public function __construct(OrderRequest $orderRequest)
    {
        $this->middleware('auth');

        $this->orderRequest = $orderRequest;
    }

    public function index()
    {
        //return redirect('/home');
    }

    public function reserveConferenceRoom(Request $request)
    {
        $event = 'reserve_conference_room';

        $fields['Date'] = $request->date;
        $fields['Start Time'] = $request->beginTime;
        $fields['End Time'] = $request->endTime;

        if($request->parking) {
            $fields['Reserve Parking'] = 'Reserve a ramp parking space';
        }

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Reserve Conference Room (' . $request->date . ')',
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Reserve Conference Room'.' (Agent: '.$order_request->agent->name.'): ' . $fields['Date'];

                User::sendEmail($order_request->agent, $subject,'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

    public function buyerRepSign(Request $request)
    {
        $event = 'buyer_rep_sign';

        $fields['Property Address'] = $request->address;
        $fields['Date to Install'] = $request->date;
        $fields['Date to Uninstall'] = $request->date_uninstall;
        $fields['Additional comments'] = $request->comments;

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Buyer Rep Sign: ' . $request->address,
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Buyer Rep Sign'.' (Agent: '.\Auth::user()->name.'): ' . $request->address;
                User::sendEmail($order_request->agent, $subject,'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

    public function startContract(Request $request)
    {
        $event = 'start_contract';

        $fields['Property Address'] = $request->address;
        $fields['Client(s) Name'] = $request->client_name;
        $fields['Price'] = $request->price;
        $fields['Additional comments'] = $request->comments;

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Start Buy/Sell Contract: ' . $request->address,
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Start Buy/Sell Contract'.' (Agent: '.$order_request->agent->name.'): ' . $request->address;

                User::sendEmail($order_request->agent, $subject, 'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

    public function requestTour(Request $request)
    {
        $event = 'request_tour';

        $fields['Property Address'] = $request->address;
        $fields['Price'] = $request->price;
        $fields['Beds'] = $request->beds;
        $fields['Baths'] = $request->baths;
        $fields['Square Footage(s)'] = $request->footage;
        $fields['Link to Property Pics or MLS#'] = $request->pictures_link;

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Wednesday Tour Request: ' . $request->address,
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Wednesday Tour Request'.' (Agent: '.$order_request->agent->name.'): ' . $request->address;

                User::sendEmail($order_request->agent, $subject,'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

    public function orderClientGift(Request $request)
    {
        $event = 'order_client_gift';

        $fields['Name'] = $request->name;
        $fields['Address'] = $request->address;
        $fields['Suggested Gift'] = $request->gift;
        $fields['Delivery Date'] = $request->date;

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Order Client Gift: ' . $request->name,
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Order Client Gift'.' (Agent: '.$order_request->agent->name.'): ' . $request->name;

                User::sendEmail($order_request->agent, $subject,'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }
        
        return redirect()->back();
    }

    public function requestSocialMediaPost(Request $request)
    {
        $event = 'request_social_media_post';

        $fields['Prefered date for post'] = $request->post_date;
        $fields['Post Content'] = $request->post_content;
        $fields['Photo to use'] = $request->post_photo;

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Request Social Media Post (' . $request->post_date . ')',
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Request Social Media Post'.' (Agent: '.$order_request->agent->name.'): ' . $request->post_date;

                User::sendEmail($order_request->agent, $subject,'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

    public function vendorList(Request $request)
    {
        $event = 'vendor_list';

        $fields['Service Category'] = $request->serviceCategory;
        $fields['Company Name'] = $request->companyName;
        $fields['Phone Number'] = $request->phone;
        $fields['Email Address'] = $request->email;
        $fields['Website Address'] = $request->url;

        $order_request = $this->orderRequest->create([
            'custom_name' => 'Vendor List: ' . $request->companyName,
            'agent_id' => \Auth::user()->id,
            'status' => 'Received',
            'public_notes' => HelperString::arrayToStringWithBreakLines($fields),
            'private_notes' => '',
        ]);

        if ( $order_request ) {
            $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order_request, $fields);
            if ( ! $sent ) {
                $subject = 'Vendor List'.' (Agent: '.$order_request->agent->name.'): ' . $request->companyName;

                User::sendEmail($order_request->agent, $subject,'emails.agent_common', $fields);
                User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
            }

            session()->flash('message', 'Your request has been sent!');
            session()->flash('alert-class', 'alert-success');
        } else {
            session()->flash('message', 'Request has not been sent');
            session()->flash('alert-class', 'alert-danger');
        }

        return redirect()->back();
    }

}
