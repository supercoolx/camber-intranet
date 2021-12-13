<?php

namespace App\Http\Controllers;

use App\Facades\EmailPattern;
use App\Helpers\Info;
use App\Order;
use App\Field;
use App\User;
use App\Section;
use App\Subsection;
use App\OrderRequest;
//use App\Subsection;
use App\Mail\OrderSend;
use App\Mail\StatusRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function updateRequest(Request $request)
    {
        $event = 'update_request';

        $orderRequest = OrderRequest::find($request->request_id);
        $orderRequest->public_notes = $request->public_notes;
        $orderRequest->private_notes = $request->private_notes;
        $orderRequest->updated_at = $request->date;
        $orderRequest->save();

        if ( $order = $orderRequest->order ) {
            $agent = $order->agent;
            $address = $order->name;
        } else {
            $agent = $orderRequest->agent;
            $address = 'unassigned';
        }
        $task_name = $orderRequest->subsection ? $orderRequest->subsection->name : $orderRequest->custom_name;

        $subject = 'New Notes ['.($address == 'unassigned' ? $task_name.'(unassigned)' : $address).']';

        $fields = $pattern_fields = $orderRequest->requestEmailFields($orderRequest->id);

//        $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $orderRequest, $fields);
        $sent = EmailPattern::sendEmailToAgent($event, $orderRequest, $pattern_fields);
        unset($pattern_fields['Private Notes']);
        unset($pattern_fields['link']);
        $sent = EmailPattern::sendEmailToAdmin($event, $orderRequest, $pattern_fields);

        if ( ! $sent ) {
            User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);

            unset($fields['Private Notes']);
            unset($fields['link']);
            User::sendEmail($agent, $subject, 'emails.agent_common', $fields);
        }

        echo Info::setMessage("Notes has been saved. $agent->email was notified.");

        exit("");
    }

    public function updateRequestStatus(Request $request)
    {
        $event = 'update_request_status';

        $orderRequest = OrderRequest::find($request->request_id);
        if ( $order = $orderRequest->order ) {
            $agent = $orderRequest->agent ?? $order->agent;
            $request_update_at = $order->email_update_at;
        } else {
            $agent = $orderRequest->agent;
            $request_update_at = FALSE;
        }

        // Updating status
        $old_updated_at = $orderRequest->updated_at;
        $orderRequest->status = $request->subsection ?? $request->status;
        $orderRequest->save();
        DB::table('order_requests')->where('id', $orderRequest->id)->update(['updated_at' => $old_updated_at]);

        $subject = 'Request has been marked as ' . $orderRequest->status;

        $email_fields = $orderRequest->requestEmailFields($orderRequest->id);
        unset($email_fields['Private Notes']);

        $all_process_statuses = array_merge(OrderRequest::inProcessSubStatuses(), ['In Process']);

        if ( $orderRequest->status == 'Completed' ) {
            $sent = EmailPattern::sendEmailToAgent($event, $orderRequest, $email_fields);
            if ( ! $sent ) {
                User::sendEmail($agent, $subject, 'emails.agent_common', $email_fields);
            }

            echo Info::setMessage("Status has been saved. $agent->email was notified.");

        } elseif ( in_array($orderRequest->status, $all_process_statuses) ) {

            if ( $request_update_at == 'checked' ) {
                $sent = EmailPattern::sendEmailToAgent($event, $orderRequest, $email_fields);
                if ( ! $sent ) {
                    User::sendEmail($agent, $subject, 'emails.agent_common', $email_fields);
                }
                echo Info::setMessage("Status has been saved. $agent->email was notified.");
            } else {
                echo Info::setMessage("Status has been saved.");
            }

        }
        exit("");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'address' => 'required|min:5|max:255',
        ]);

    	$address = $request->input('address');
        $order = Order::where('name', 'like', $address)->first();
        if(!$order) {

            $order = new Order;
            $order->name = $address;
            $order->agent_id = \Auth::user()->id;

            $order->save();
        } else {
            //updated updated_at field for determinate last open action
            $order->touch();
        }

        return redirect()->route('orders.edit', ['order' => $order->id]);
    }

    //Not use currently
    public function storeRequest(Request $request)
    {
        $orderRequest = new OrderRequest;
        $orderRequest->custom_name = $request->input('request');
        $orderRequest->agent_id = $request->input('agent_id');
        $orderRequest->status = 'Received';
        $orderRequest->public_notes = $request->input('public_notes');
        $orderRequest->private_notes = $request->input('private_notes');
        $orderRequest->updated_at = date($request->input('date') . 'H:i:s');

        $orderRequest->save();

        echo Info::setMessage("Ad hoc request has been added");
    }

    public function show(Order $order)
    {
        //
    }

    public function edit(Order $order)
    {
        $sections = Section::all();
        $orderData = [];
        foreach ($sections as $section) {
            $subsections = $section->subsections()->get();

            foreach ($subsections as $subsection) {

                $sectionTouched = true;

                $fields = $order->fields()
                    ->where('subsection_id', $subsection->id)
                    ->orderBy('order')
                    ->get();

                if(!$fields->first()) {
                    $fields = Field::where('subsection_id', $subsection->id)
                        ->orderBy('order')
                        ->get();
                    $sectionTouched = false;
                }

                foreach($fields as $id=>$field){
                    $fields[$id]['name'] = Field::makeMoreReadable($field['name']);
                }

                ///////
                $orderRequest = OrderRequest::where('order_id', '=' ,$order->id)
                  ->where('subsection_id', '=', $subsection->id)
                  ->orderBy('order_id')
                  ->value('status');
                //////

                $orderData[$section->name][$subsection->id]['section_touched'] = $sectionTouched;
                $orderData[$section->name][$subsection->id]['name'] = $subsection->name;
                $orderData[$section->name][$subsection->id]['status'] = $orderRequest;
                $orderData[$section->name][$subsection->id]['subheader'] = $subsection->subheader;
                $orderData[$section->name][$subsection->id]['fields'] = $fields;
            }
        }

        $assistants = User::whereHas('roles', function($q){
            $q->where('name', 'assistant');
        })->get();

        $checked = Order::where('id', '=', $order->id)->value('email_update_at');

        //dd($assistants);
        //$assistants = $users->withRole('assistant')->get();
        return view('orders.edit', compact('assistants', 'checked', 'order', 'orderData'));
    }

    public function update(Request $request, $orderId)
    {

        $event = 'listing_update';

        $order = Order::findOrFail($orderId);
        $order->email_update_at = $request->email_update_at;
        $order->assistant_id = User::findOrFail($request->assistant)->id;
        $order->save();

        $fields = Field::all();
        $requiredFields['required']['assistant'] = 'required';
        $requiredFields['messages'] = [];
        $formFields = $request->input('field');
        $subsections = $request->input('subsection');
        if(empty($subsections)) {
            return redirect()->back();
        }

        foreach ($fields as $field) {
            if(isset($formFields[$field->id])) {
                if($field->required) {
                    $requiredFields['required']['field.'.$field->id.'.value'] = 'required';
                    $requiredFields['messages']['field.'.$field->id.'.value.required'] = 'The '.$field->name.' is required.';
                }
            }
        }

        $this->validate($request, $requiredFields['required'], $requiredFields['messages']);

        $syncFields = $mailMsg = [];

        $defaultSubsection = Subsection::where('name', 'Seller Info and Price')->first();
        if(!isset($subsections[$defaultSubsection->id])){
            $subsections[$defaultSubsection->id] = 1;
        }

        foreach($subsections as $subsectionId => $value) {
            $subsection = Subsection::findOrFail($subsectionId);
            $section = Section::findOrFail($subsection->section_id);
            $subsectionFields = $subsection->fields()->orderBy('order')->get();

            foreach($subsectionFields as $subsectionField) {
                $formFieldValue = isset($formFields[$subsectionField->id]) ? $formFields[$subsectionField->id]['value'] : '';

                $syncFields[$subsectionField->id] = [
                    'value' => $formFieldValue,
                    'updated_at' => date('Y-m-d H:i:s')
                     ];

                if(strlen($formFieldValue) > 0) {
                    $section_name = $section->name == 'New Listing' ? 'New Request' : $section->name;
                    $mailMsg[$section_name][$subsection->name][] = [
                            'name' => $subsectionField->name,
                            'value' => $formFieldValue
                    ];
                }
            }

            $orderRequest = new OrderRequest;
            $orderRequest->order_id = $orderId;
            $orderRequest->subsection_id = $subsectionId;
            $orderRequest->status = 'Received';
            $orderRequest->save();

        }

//        \Errors::logInfo($syncFields);

        if( !empty($syncFields) ) {
            foreach ($syncFields as $fieldId => $values) {
                $order->fielsdWithoutMetaData()->syncWithoutDetaching([
                    $fieldId => $values
                ]);
            }
        }

        $data_fields['Listing Fields'] = EmailPattern::listingDataToHTML($mailMsg);

        $sent = EmailPattern::sendEmailToAgentAndAdmin($event, $order, $data_fields);
        if ( ! $sent ) {
            $subject = 'Listing'. ' (Agent: '.$order->agent->name.')';
            User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject,'emails.order', $mailMsg, $order);
            User::sendEmail($order->agent, $subject,'emails.order', $mailMsg, $order);
        }

        session()->flash('message', 'Listing\'s requests has been saved');
        session()->flash('alert-class', 'alert-success');

        if ( \Auth::user()->isAdmin() ) {

            return redirect()->route('dashboard.index');
        }

        return redirect()->route('orders.edit', ['order' => $order->id]);
    }


    public function destroy(Order $order)
    {
        $order->delete();

        return redirect('home');
    }
}
