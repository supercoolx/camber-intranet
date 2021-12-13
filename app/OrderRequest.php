<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{

    protected $fillable = [
        'order_id', 'custom_name', 'agent_id', 'status', 'public_notes', 'private_notes', 'updated_at'
    ];

    static $specialSubsections = [
        10 => [
            'Proof created or information sent to designer',
            'Proof sent to agent',
            'Proof updated',
            'Updated proof sent to agent',
            'Proof approved',
            'Printer selected by agent',
            'Proof sent to printer',
            'Completed',
        ],
        25 => [
            'Proof created or information sent to designer',
            'Proof sent to agent',
            'Proof updated',
            'Updated proof sent to agent',
            'Proof approved',
            'Printer selected by agent',
            'Proof sent to printer',
            'Completed',
        ],
        12 => [
            'File sent to printer',
            'Printing Confirmation sent to Agent',
            'Completed',
        ],
        16 => [
            'Proof created',
            'Proof sent to agent',
            'Proof updated',
            'Updated proof sent to agent',
            'Proof approved',
            'eBlast scheduled',
            'Completed',
        ]
    ];

    public function fields2()
    {
        return $this->belongsToMany('\App\Field')->withPivot('value');
    }

    public function fielsdWithoutMetaData2()
    {
        return $this->belongsToMany('App\Field');
    }

    public function assistant2()
    {
        return $this->hasOne('App\User', 'id', 'assistant_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function agent()
    {
        return $this->belongsTo('App\User', 'agent_id');
    }

    public function subsection()
    {
        return $this->belongsTo('App\Subsection');
    }

    public function getRequests()
    {
        $details = FieldOrder::
            select(
                    'order_requests.id',
                    'order_requests.public_notes',
                    'order_requests.private_notes',
                    'order_requests.subsection_id',
                    'order_requests.updated_at as date_modified',
                    'subsections.name',
                    'field_order.order_id',
                    'field_order.value',
                    'field_order.updated_at',
                    'users.name AS agent_name',
                    'users.id AS agent_id',
                    'order_requests.status',
                    'fields.name AS field_name',
                    'orders.name as address'
            )
            ->leftJoin('fields', 'field_order.field_id', '=', 'fields.id')
            ->leftJoin('subsections', 'fields.subsection_id', '=', 'subsections.id')
            ->leftJoin('orders', 'field_order.order_id', '=', 'orders.id')
            ->leftJoin('order_requests', function($join) {

                $join->on('order_requests.order_id', '=', 'orders.id');
                $join->on('order_requests.subsection_id', '=', 'subsections.id');
            })
            ->leftJoin('users', 'users.id', '=', 'orders.agent_id')
            ->whereNotNull('value')
            ->whereNotNull('order_requests.id')
            ->where('subsections.id', '<>', 1)
            ->orderBy('date_modified', 'desc')
            ->get();

        $requests = [];
        $extra = [];
        //removing duplicates
        foreach ($details as $detail) {

            $requests[$detail->id] = $detail;
            $requests[$detail->id]->details = array();
            //TODO we don't need it
            // $requests[$detail->id]->uid = $detail->id;
        }

        foreach ($details as $detail) {
            $extra[$detail->id][$detail->field_name] = "<span class='text-label-dashboard'>" . $detail->field_name . ":</span> " . $detail->value;
        }

        foreach ($details as $detail) {
            $requests[$detail->id]->details = $extra[$detail->id];
        }

        foreach ($details as $detail) {
            $requests[$detail->id]['subsection'] = $this->getSpecialSubsections($detail->subsection_id);
        }

        return $requests;
    }

    public function getAdHocRequests()
    {
        $details = OrderRequest::
            select('*', 'order_requests.id as req_id', 'order_requests.updated_at as date_modified', 'users.name as agent_name', 'order_requests.custom_name as name', 'orders.name as address', 'order_requests.agent_id as agent_id')
                ->leftJoin('users', 'users.id', '=', 'order_requests.agent_id')
                ->leftJoin('orders', 'orders.id', '=', 'order_requests.order_id')
    //                ->whereNull('order_id')
                ->whereNull('subsection_id')
                ->orderBy('date_modified', 'desc')
                ->get();
        $requests = [];
        $extra = [];
        //removing duplicates
        foreach ($details as $detail) {
            $requests[$detail->req_id] = $detail;
            $requests[$detail->req_id]->details = array();
            //TODO we don't need it
            // $requests[$detail->id]->uid = $detail->id;
        }

//        foreach($details as $detail){
//            $extra[$detail->id][$detail->field_name] = "<span class='text-primary'>".$detail->field_name.":</span> ".$detail->value;
//        }
//
//
//        foreach($details as $detail){
//            $requests[$detail->id]->details = $extra[$detail->id] ;
//        }
        return $requests;
    }

    public function requestEmailFields($id)
    {
        $orderRequest = OrderRequest::find($id);
        if ( $order = $orderRequest->order ) {
            $address = $order->name;
        } else {
            $address = 'unassigned';
        }
        $task_name = $orderRequest->subsection ? $orderRequest->subsection->name : $orderRequest->custom_name;

        $fields['Address'] = $address;
        $fields['Task'] = $task_name;
        $fields['Status'] = $orderRequest->status;
        $fields['Public Notes'] = $orderRequest->public_notes;
        $fields['Private Notes'] = $orderRequest->private_notes;
        $fields['link'] = route('dashboard.index').'#'.$orderRequest->id;

        return $fields;
    }

    public static function isSpecialSubsection($id)
    {
        return isset(self::$specialSubsections[$id]) ? true : false;
    }

    public static function getSpecialStatuses($id)
    {
        return self::$specialSubsections[$id];
    }

    private function getSpecialSubsections($id)
    {
        /*switch($id){
            case 10:
               return self::$specialSubsections[$id];
            break;
            case 12:
               return self::$specialSubsections[$id];
            break;
            case 16:
               return self::$specialSubsections[$id];
            break;
            case 25:
               return self::$specialSubsections[$id];
            break;
        }*/
        if($this->isSpecialSubsection($id)){
            return self::$specialSubsections[$id];
        }
    }

    public static function inProcessSubStatuses()
    {
        $specialSubsections = [];
        foreach (self::$specialSubsections as $specialSection) {
            $specialSubsections = array_merge($specialSubsections, $specialSection);
        }
        foreach ($specialSubsections as $k => $specialSubsection) {
            if ($specialSubsection == 'Completed') {
                unset($specialSubsections[$k]);
            }
        }

        return $specialSubsections;
    }
}