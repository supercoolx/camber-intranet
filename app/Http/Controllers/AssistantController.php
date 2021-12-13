<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AssistantController extends Controller
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
        $users = User::whereHas('roles', function($q){
            $q->where('name', 'assistant');
        })->get();

        return view('admin.assistants.list', compact('users'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
