<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use DateTime;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::whereHas('roles', function($q){
        //     $q->where('name', 'transaction');
        // })->get();
        
        // $agents = User::orderBy('name', 'asc')->whereHas('roles', function($q) {
        //     $q->where('name', 'agent');
        // })->get();
        
        $transactions = Transaction::latest()->with('user')->get();
        $sql = "SELECT * FROM users a, (
            SELECT agent FROM transactions GROUP BY agent
            ) b WHERE a.id = b.agent";
        $agents = DB::select($sql);
        return view('admin.transactions.list', compact(['transactions','agents']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        $agents = User::orderBy('name', 'asc')->whereHas('roles', function($q) {
            $q->where('name', 'agent');
        })->get();
        // $sql = "select * from users left join roles on users.role = roles.name order by users.name";
        // $users = DB::select($sql);
        
        $orders = Order::orderBy('updated_at', 'desc')->get();
        //$transactions = Transaction::orderBy('createddate', 'asc')->get();
        return view('admin.transactions.create', ['agents' => $agents, 'orders' => $orders]); //, 'transactions' => $transactions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /* $this->validate($request, [
            'name' => 'max:255',
            'email' => 'required|email|unique:users',
            // 'password' => 'required|min:4',
    	]);*/
        $transaction = new Transaction;
    	$transaction->type = $request->type;
    	$transaction->agent = $request->agent? $request->agent:'';
    	$transaction->split = $request->split ? $request->split : 0;
    	$transaction->closedate = $request->closedate;
    	$transaction->address = $request->address?$request->address:'';
    	$transaction->price = $request->price ? $request->price:0 ;
    	$transaction->coop_fee = $request->coop_fee ? $request->coop_fee:0;
    	$transaction->referral = $request->referral ? $request->referral:0;
    	$transaction->expense = $request->expense ? $request->expense : 0;
    	$transaction->check = $request->check ? $request->check : 'Open';
    	$transaction->notes = $request->notes ? $request->notes : '';
        // $user->password = bcrypt($input['password']);
    	$transaction->save();
        //$transaction->addRole('transaction');
        return redirect('admin/transactions');
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
    	return view('admin.transactions.edit', compact('user'));
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
        
        
        $request->type = trim($request->input('type', ''));
    	$request->agent = trim($request->input('agent', ''));
    	$request->split = trim($request->input('split', ''));
    	$request->closedate = trim($request->input('closedate', ''));
    	$request->address = trim($request->input('address', ''));
    	$request->saleprice = trim($request->input('saleprice', ''));
    	$request->gci = trim($request->input('gci', ''));
    	$request->referral = trim($request->input('referral', ''));
    	$request->credit = trim($request->input('credit', ''));
    	$request->expense = trim($request->input('expense', ''));
    	$request->status = trim($request->input('status', ''));
    	$request->notes = trim($request->input('notes', ''));
        $request->name = trim($request->input('name', ''));
        $request->email =trim($request->email);
        
        /*$id = trim($request->input('user_id'));

        $user = Transaction::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        if($user->email != $request->input('email')){
            $this->validate($request, [
                'email' => 'required|email|unique:users',
            ]);
        }*/
        
        $transaction->type = $request->type;
    	$transaction->agent = $request->agent;
    	$transaction->split = $request->split;
    	$transaction->closedate = $request->closedate;
    	$transaction->address = $request->address;
    	$transaction->saleprice = $request->saleprice;
    	$transaction->gci = $request->gci;
    	$transaction->referral = $request->referral;
    	$transaction->credit = $request->credit;
    	$transaction->expense = $request->expense;
    	$transaction->status = $request->status;
    	$transaction->notes = $request->notes;


        // if($request->has('password') && strlen(trim($request->input('password')))) {
        //     $this->validate($request, [
        //         'password' => 'min:4',
        //     ]);
        //     $user->password = bcrypt($request->password);
        // }

        $transaction->save();

        return redirect('/admin/transactions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($agent)
    {
        $user = User::findOrFail($agent);
        $user->delete();

        return redirect('/admin/transactions');
    }*/
}
