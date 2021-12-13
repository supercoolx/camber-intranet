<?php

namespace App\Http\Controllers;

use App\Facades\EmailPattern;
use App\Helpers\Info;
use App\Order;
use App\OrderRequest;
use App\User;
use Illuminate\Http\Request;

class OrderRequestController extends Controller
{
    public $orderRequest;
    public $order;
    public $user;

    public function __construct(OrderRequest $orderRequest, Order $order, User $user)
    {
        $this->orderRequest = $orderRequest;
        $this->order = $order;
        $this->user = $user;
    }

    public function store(Request $request)
    {
        $agent = $this->user->where('id', $request->agent_id)->first();
        $exist_order = $this->order->where('name', $request->address)->first();

        if ( $request->request_type == 'with_address_form' ) {

            $this->validate($request, ['address' => 'required|min:10|max:255']);
            if ( !$exist_order && $request->address ) {
                $new_order = $agent->orders()->create([
                    'name' => $request->address,
                    'agent_id' => $agent->id,
                ]);

                if ( $new_order ) {
                    return redirect()->route('orders.edit', ['order' => $new_order->id]);
                }
            } else {
                return redirect()->route('orders.edit', ['order' => $exist_order->id]);
            }

        } elseif ( $request->request_type == 'ad_hoc_form' ) {

            $data = [
                'custom_name' => $request->custom_name,
                'agent_id' => $agent->id,
                'status' => 'Received',
                'public_notes' => $request->public_notes,
                'private_notes' => $request->private_notes,
                'updated_at' => date($request->input('date') . 'H:i:s'),
            ];

            if ( $exist_order ) {
                $data['order_id'] = $exist_order->id;
            }

            $order_request = $this->orderRequest->create($data);

            if ( $order_request ) {

                $event = 'create_ad_hoc_request';

                $fields = $this->orderRequest->requestEmailFields($order_request->id);

                $sent = EmailPattern::sendEmailToAgent($event, $order_request, $fields);
                if ( ! $sent ) {
                    $subject = 'Ad Hoc Request - ['.$agent->name.']' . ($order_request->order ? ' - ['.$order_request->order->name.']' : '');

                    // Notify for admin
                    User::sendEmail(env('MAIL_ADMIN_ADDRESS'), $subject, 'emails.agent_common', $fields);
                    unset($fields['Private Notes']);

                    // Notify for agent
                    User::sendEmail($agent, $subject, 'emails.agent_common', $fields);
                }

                session()->flash('message', 'Ad hoc request has been added');
                session()->flash('alert-class', 'alert-success');
            } else {
                session()->flash('message', 'Ad hoc request has not been added');
                session()->flash('alert-class', 'alert-danger');
            }

        }
        return redirect()->route('dashboard.index');
    }
}
