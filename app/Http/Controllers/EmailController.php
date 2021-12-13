<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;
use App\Facades\EmailPattern;

class EmailController extends Controller
{
    const VARS = [
        '[agent_name]' => 'agent name',
        '[agent_email]' => 'agent\'s email address',
        '[admin_email]' => 'admin\'s email address',
        '[task_name]' => 'task name or custom name if request is ad hoc',
        '[public_fields]' => 'Address, Task, Status, Public Notes, link',
        '[private_fields]' => 'Address, Task, Status, Public Notes, Private Notes, link',
        '[old_status]' => 'Current status of task',
        '[new_status]' => 'New status of task',
        '[date]' => 'Date create'
    ];

    private $config;

    public function __construct()
    {
        $this->config = config('emailpattern');
    }

    public function index()
    {
        $emails = Email::all();

        return view('admin.emails.index', compact('emails'));
    }


    public function show(Email $email)
    {
        //
    }

    public function edit(Email $email)
    {

        $global_vars = $this->config['global'];
        $specific_vars = $this->config[$email->event];

        if ( in_array($email->event, ['listing_update'])) {
            unset($global_vars['[task_name]']);
            unset($global_vars['[public_fields]']);
            unset($global_vars['[private_fields]']);
            unset($global_vars['[status]']);
        }
        $global_vars['[link]'] = 'For admin only';

        return view('admin.emails.edit', compact('email', 'global_vars', 'specific_vars'));
    }

    public function update(Request $request, Email $email)
    {
        $data = $this->validate($request, [
            'description'   => 'required|max:500',
            'subject_agent' => 'required|max:500',
            'body_agent'    => 'required|max:500',
            'subject_admin' => 'required|max:500',
            'body_admin'    => 'required|max:500',
            'is_active'     => 'boolean|max:500',
        ]);

        if ( empty($data['is_active']) ) {
            $data['is_active'] = 0;
        }

        $email->update($data);

        return redirect('/admin/emails');
    }

}
