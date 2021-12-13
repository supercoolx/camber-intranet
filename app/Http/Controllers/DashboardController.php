<?php

namespace App\Http\Controllers;

use App\Order;
use App\Subsection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\User;
use App\FieldOrder;
use App\OrderRequest;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //TODO not logged by my system
        //$zzz = $aaa;
        $orderRequest = new OrderRequest;
        $regularRequests = $orderRequest->getRequests();
        $adHocRequests = $orderRequest->getAdHocRequests();
        $orders = Order::orderBy('updated_at', 'desc')->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $requests = array_merge($regularRequests, $adHocRequests);

        $sort_order = $request->order == 'asc' ? SORT_ASC : SORT_DESC;
        $sort_orderby = $request->orderby ?? 'date_modified';

        $this->array_sort_by_column($requests, $sort_orderby, $sort_order);
        $requests = $this->sortByStatus($requests);

        if ( $request->status ) {
            $requests = $this->filterByStatus($requests, $request->status);
        } else {
            $requests = $this->filterByStatus($requests, 'in_process');
        }

        if ( $request->agent ) {
            $requests = $this->filterByAgent($requests, $request->agent);
        }

        $table_parameters = ['address' => [], 'request' => [], 'agent_name' => [], 'date_modified' => [], 'status' => []];
        foreach ($table_parameters as $k => $parameter) {
            $table_parameters[$k]['orderby'] = $k;
            if (request()->order == 'asc') {
                $table_parameters[$k]['order'] = 'desc';
            } else {
                $table_parameters[$k]['order'] = 'asc';
            }
            if (request()->page) {
                $table_parameters[$k]['page'] = request()->page;
            }
            if (request()->agent) {
                $table_parameters[$k]['agent'] = request()->agent;
            }
            if (request()->status) {
                $table_parameters[$k]['status'] = request()->status;
            }
        }


        $itemCollection = collect($requests);

        $perPage = 30;

        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        $paginatedItems = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        $paginatedItems->setPath('dashboard');

        $agents = User::orderBy('name', 'asc')->whereHas('roles', function($q) {
            $q->where('name', 'agent');
        })->get();

        return view('admin.dashboard', ['requests' => $paginatedItems, 'agents' => $agents, 'orders' => $orders, 'table_parameters' => $table_parameters]);
    }

    public function agentDashboard(Request $request)
    {
        if (! \Auth::user()->isAgent() ) {
            return redirect()->route('login');
        }
        $orderRequest = new OrderRequest;
        $regularRequests = $orderRequest->getRequests();
        $adHocRequests = $orderRequest->getAdHocRequests();
        $orders = Order::orderBy('updated_at', 'desc')->get();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $requests = array_merge($regularRequests, $adHocRequests);

        $sort_order = $request->order == 'asc' ? SORT_ASC : SORT_DESC;
        $sort_orderby = $request->orderby ?? 'date_modified';

        $this->array_sort_by_column($requests, $sort_orderby, $sort_order);
        $requests = $this->sortByStatus($requests);
        $requests = $this->filterByAgent($requests, \Auth::user()->id);
        if ( $request->status ) {
            $requests = $this->filterByStatus($requests, $request->status);
        } else {
            $requests = $this->filterByStatus($requests, 'in_process');
        }

        $table_parameters = ['address' => [], 'request' => [], 'date_modified' => [], 'status' => []];
        foreach ($table_parameters as $k => $parameter) {
            $table_parameters[$k]['orderby'] = $k;
            if (request()->order == 'asc') {
                $table_parameters[$k]['order'] = 'desc';
            } else {
                $table_parameters[$k]['order'] = 'asc';
            }
            if (request()->page) {
                $table_parameters[$k]['page'] = request()->page;
            }
            if (request()->status) {
                $table_parameters[$k]['status'] = request()->status;
            }
        }

        $itemCollection = collect($requests);

        $perPage = 30;

        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        $paginatedItems = new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);

        $paginatedItems->setPath('dashboard');

        return view('agent.dashboard', ['requests' => $paginatedItems, 'orders' => $orders, 'table_parameters' => $table_parameters]);
    }

    public function array_sort_by_column(&$arr, $col, $dir = SORT_DESC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    private function sortByStatus($arr)
    {
        $count = count($arr);
    
        for($i=0;$i<($count-1);$i++){
            $currentDate = $arr[$i]->date_modified;
            $next = $i+1;
    
            while(isset($arr[$next]) && $arr[$next]->date_modified == $currentDate){
             //   echo "checking $i - $next \r\n";
                if($arr[$next]->status != 'Completed' && $arr[$i]->status == 'Completed'){
                    //swap
                    $temp = $arr[$i];
                    $arr[$i] = $arr[$next];
                    $arr[$next] = $temp;
                }
                $next++;
            }
        }
        return $arr;
    }

    private function filterByStatus($arr, $status_uri)
    {
        $specialSubsections = OrderRequest::inProcessSubStatuses();

        switch ($status_uri) {
            case('completed'):
                $statuses = ['Completed'];
                break;
            case('in_process'):
                $statuses = array_merge($specialSubsections, ['In Process', 'Received']);
                break;
            default:
                $statuses = FALSE;
                break;
        }
        if ($statuses) {
            foreach ($arr as $i => $item) {
                if ( !in_array($item->status, $statuses) ) {
                    unset($arr[$i]);
                }
            }
        }

        return $arr;
    }

    private function filterByAgent($arr, $agent_id)
    {
        if ( User::find($agent_id) ) {
            $y=0;
            foreach ($arr as $i => $item) {
//                echo $item->agent_id . ' = ' . $agent_id . ' | ';
                if ($item->agent_id != $agent_id) {
                    unset($arr[$i]);
                }
                $y++;
            }
        }

        return $arr;
    }

    public function requests()
    {
   
        $orderRequest = new OrderRequest;
        $regularRequests = $orderRequest->getRequests();
        $adHocRequests = $orderRequest->getAdHocRequests();

        $requests = array_merge($regularRequests, $adHocRequests);
    
        $agents = User::whereHas('roles', function($q){
            $q->where('name', 'agent');
        })->get();
        
        echo json_encode($requests);


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.assistants.create');
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'max:255',
            'email' => 'required|email|unique:users',
            // 'password' => 'required|min:4',
    	]);
    	$input = $request->all();

        $user = new User;
    	$user->name = trim($request->input('name', ''));
        // $user->password = bcrypt($input['password']);
        $user->password = '';
    	$user->email = trim($input['email']);
    	$user->save();

        $user->addRole('assistant');

        return redirect('admin/assistants');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$user = User::findOrFail($id);
    	return view('admin.assistants.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->name = trim($request->input('name', ''));
        $request->email =trim($request->email);
        $id = trim($request->input('user_id'));

        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        if($user->email != $request->input('email')){
            $this->validate($request, [
                'email' => 'required|email|unique:users',
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        // if($request->has('password') && strlen(trim($request->input('password')))) {
        //     $this->validate($request, [
        //         'password' => 'min:4',
        //     ]);
        //     $user->password = bcrypt($request->password);
        // }

        $user->save();

        return redirect('/admin/assistants');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($agent)
    {
        $user = User::findOrFail($agent);
        $user->delete();

        return redirect('/admin/assistants');
    }
}
