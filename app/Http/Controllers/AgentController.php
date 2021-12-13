<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReferral;
use Session;
use App\Transaction;
class AgentController extends Controller
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
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'agent');
        })->get();

        return view('admin.agents.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.agents.create');
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
            'password' => 'required|min:4',
        ]);

        $input = $request->all();
        $user = new User;

        $user->name = $request->input('name', '');
        $user->phone = $request->input('phone', '');
        $user->password = bcrypt($input['password']);
        $user->email = $input['email'];
        $user->secondary_email = $request->input('secondary_email', '');
        $user->remember_token = str_random(10);
        $user->email_verified_at = now();
        $user->split = $request->input('split');
        $user->crossover = $request->input('crossover');
        $user->crossover_split = $request->input('crossover_split');
        if($file = $request->file('photo')){
            $name = time().time().'.'.$file->getClientOriginalExtension();
            $target_path = public_path('uploads/agents/photos');
            if($file->move($target_path, $name)) {
                $user->photo = '/uploads/agents/photos/'.$name;
            }
        }
        else{
            $user->photo = '/uploads/agents/photos/no_photo.png';
        }
        $user->save();
        $referrerLink = $request->input('referrer_link', $user->getEncodeId());

        $camAccount = $request->input('link', '');

        $user->addRole('agent', $camAccount, $referrerLink);

        return redirect('admin/agents')->with('agent-added', 'Agent has been added successfully! Referrer Link: ' . route('agent.show', ['hash' => $user->getEncodeId()]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($hash)
    {

        $agent = User::whereHas('roles', function ($q) use ($hash) {
            $q->where('name', 'agent')
                ->where('referrer_link', $hash);
        })->firstOrFail();

        return view('agent.contact', compact('agent'));
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
        $camAccount = User::findOrFail($id)->roles()
            ->where('roles.name', 'agent')->first()->pivot->cam_account;

        $referrerLink = User::findOrFail($id)->roles()
            ->where('roles.name', 'agent')->first()->pivot->referrer_link;

        return view('admin.agents.edit', compact('user', 'camAccount', 'referrerLink'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        //exit("Under Maintenance");
        $user = \Auth::user();

        $camAccount = User::findOrFail($user->id)->roles()
            ->where('name', 'agent')->first()->pivot->cam_account;

        return view('agent.settings', compact('user', 'camAccount'));
    }
    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        //exit("Under Maintenance");
        $user = \Auth::user();

        $camAccount = User::findOrFail($user->id)->roles()
            ->where('roles.name', 'agent')->first()->pivot->cam_account;

        return view('agent.profile', compact('user', 'camAccount'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request)
    {
        $request->name = $request->input('name', '');
        $id = $request->input('user_id');

        $user = User::findOrFail($id);

        // dd($request->photo);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'photo' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($user->email != $request->input('email')) {
            $this->validate($request, [
                'email' => 'required|email|unique:users',
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->input('phone', '');
        $user->secondary_email = $request->input('secondary_email', '');

        if ($request->has('password') && strlen(trim($request->input('password')))) {
            $this->validate($request, [
                'password' => 'min:4',
            ]);
            $user->password = bcrypt($request->password);
        }

        if ($request->photo) {
            //delete old image
            if ($user->photo) {
                \Storage::delete($user->photo);
            }

            $path = $request->file('photo')
                ->store('public/photos');
            $user->photo = $path;
        }

        $user->save();


        $referrerLink = $request->input('referrer_link', $user->getEncodeId());
        $camAccount = $request->input('link', '');
        $user->addRole('agent', $camAccount, $referrerLink);

        if ($user->hasRole('admin')) {
            return redirect('/admin/agents');
        } else {
            return redirect('/profile');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->name = $request->input('name', '');
        $id = $request->input('user_id');

        $user = User::findOrFail($id);

        // dd($request->photo);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'photo' => 'image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($user->email != $request->input('email')) {
            $this->validate($request, [
                'email' => 'required|email|unique:users',
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->input('phone', '');
        $user->secondary_email = $request->input('secondary_email', '');
        $user->split = $request->input('split', 0);
        $user->crossover = $request->input('crossover', 0);
        $user->crossover_split = $request->input('crossover_split', 0);
        if ($request->has('password') && strlen(trim($request->input('password')))) {
            $this->validate($request, [
                'password' => 'min:4',
            ]);
            $user->password = bcrypt($request->password);
        }

        if ($request->photo) {
            //delete old image
            if ($user->photo) {
                \Storage::delete($user->photo);
            }

            $path = $request->file('photo')
                ->store('public/photos');
            $user->photo = $path;
        }

        $user->save();



        $referrerLink = $request->input('referrer_link', $user->getEncodeId());
        $camAccount = $request->input('link', '');
        $user->addRole('agent', $camAccount, $referrerLink);

        if ($request->input('adminpanel') == 1) {
            Session::flash('message', 'Agent settings has been updated');
            Session::flash('alert-class', 'alert-success');
            return redirect('/admin/agents');
        } else {
            Session::flash('message', 'Your settings has been updated');
            Session::flash('alert-class', 'alert-success');
            return redirect('/profile');
        }
        // return redirect('/admin/agents');
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

        if ($user->photo) {
            \Storage::delete($user->photo);
        }

        $user->delete();
        Session::flash('message', 'Agent has been deleted');
        Session::flash('alert-class', 'alert-success');

        return redirect('/admin/agents');
    }

    public function bringFriend(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|min:2|max:255',
                'last_name' => 'required|min:2|max:255',
                'email' => 'required|email',
                'friend_name' => 'required|min:2|max:255',
                'friend_last_name' => 'required|min:2|max:255',
                'friend_email' => 'required|email',
            ],
            [
                'last_name' => 'The Last Name is required.',
                'friend_name' => 'The Friend name is required.',
                'friend_last_name' => 'The Friend last name is required.',
                'friend_email' => 'Friend\' Email is required.',
            ]
        );

        $agent = User::findOrFail((int) $request->agent);
        $fields['My Name'] = $request->name;
        $fields['My Last Name'] = $request->last_name;
        $fields['My Email'] = $request->email;
        if ($request->phone) {
            $fields['My Phone'] = $request->phone;
        }

        $fields['Friend Name'] = $request->friend_name;
        $fields['Friend Last Name'] = $request->friend_last_name;
        $fields['Friend Email'] = $request->friend_email;
        if ($request->friend_phone) {
            $fields['Friend Phone'] = $request->friend_phone;
        }

        if ($request->notes) {
            $fields['Notes'] = $request->notes;
        }

        User::sendEmail($agent, 'New Referall', 'emails.new_referral', $fields);

        return redirect('https://camberrealty.com/thank-you/?preview_id=31781&amp;preview_nonce=4cd7f35a76&amp;_thumbnail_id=-1&amp;preview=true');
    }
    public function report(){
        return view('agent.report');
    }
}
