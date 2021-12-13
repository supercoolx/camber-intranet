<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function($user)
        {
            $orders = $user->admin_orders;
            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $requests = $order->requests;
                    if (count($requests) > 0) {
                        foreach ($requests as $request) {
                            $request->delete();
                        }
                    }
                    $order->delete();
                }
            }
        });
    }

    /**
     * create hash by user id
     *
     * @param [integer] $value
     * @return string
     */
    public function getEncodeId()
    {
        //'padding' sequence is not required for decoding, but it(%3D) conflicts with jquery
        //https://en.wikipedia.org/wiki/Base64
        return str_replace('%3D', '', urlencode(base64_encode($this->id)));
    }

    /**
     * Add role to user
     *
     * @param [string] $roleName
     * @param [string] $referrerLink
     * @return void
     */
    public function addRole($roleName, $camAccount = '', $referrerLink = '')
    {
        $role = Role::where('name', $roleName)->first();
        return $this->roles()->syncWithoutDetaching([
            $role->id => [
                'cam_account' => $camAccount,
                'referrer_link' => $referrerLink
            ]
        ]);
    }

    /**
     * get role to user
     *
     * @param [string] $roleName
     * @return void
     */
    public function getRoleAttribute($roleName, $attribute)
    {
        if(isset($this->roles()->where('roles.name', $roleName)->first()->pivot->$attribute)) {
           return $this->roles()->where('roles.name', $roleName)->first()->pivot->$attribute;
        }

        return '';
    }

    /**
     * The roles without meta data
     */
    public function rolesdWithoutMetaData()
    {
        return $this->belongsToMany('App\Role');
    }


    /**
     * The roles
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withPivot('cam_account');
    }

    /**
     * check role function
     *
     * @param [array] $roles
     * @return boolean
     */
    public function hasRoles($roles) {
        return $this->rolesdWithoutMetaData()->whereIn('name', $roles)->first();
    }

    /**
     * check role function
     *
     * @param [string] $roleName
     * @return boolean
     */
    public function hasRole($roleName) {

        if ( $this->roles()->where('name', $roleName)->first() ) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    /**
     * check check if user admin
     *
     * @param [string] $roleName
     * @return boolean
     */
    public function isAdmin() {
        return $this->hasRole('admin');
    }

    public function isAgent()
    {
        return $this->hasRole('agent');
    }

      /**
     * The assistant received last changes
     */
    public function orders()
    {
        return $this->hasMany('App\Order', 'agent_id', 'id')->orderBy('updated_at', 'desc');
    }

    public function admin_orders()
    {
        return $this->hasMany('App\Order', 'assistant_id', 'id')->orderBy('updated_at', 'desc');
    }

    public static function sendEmail($to, $subject, $template, $fields, $order = false) {

        if(is_object($to)) {
            $toEmail = $to->email;
            $toSecondary = $to->secondary_email ? $to->secondary_email : false;
        } else {
            $toEmail = $to;
            $toSecondary = false;
        }

        $email_data = [
            'subject' => $subject,
            'fields' => $fields,
        ];
        if ( $order ) {
            $email_data['order'] = $order;
        }

        $view = \View::make($template, $email_data);
        $html = $view->render();

        // Create the Transport
        $transport = (new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT')))
          ->setUsername(env('MAIL_USERNAME'))
          ->setPassword(env('MAIL_PASSWORD'));

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($subject))
            ->setFrom([env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME')])
            ->setTo([$toEmail])
            ->setBody($html, 'text/html');
        $result = $mailer->send($message);

        if ( $toSecondary ) {
            $message2 = (new \Swift_Message($subject))
                ->setFrom([env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME')])
                ->setTo($toSecondary)
                ->setBody($html, 'text/html');
            $result = $mailer->send($message2);
        }

        return $result;
    }

    public static function sendPatternEmail($to, $subject, $body, $template, $order = false) {

        if(is_object($to)) {
            $toEmail = $to->email;
            $toSecondary = $to->secondary_email ? $to->secondary_email : false;
        } else {
            $toEmail = $to;
            $toSecondary = false;
        }

        $email_data = [
            'subject' => $subject,
            'body' => $body
        ];

        $view = \View::make($template, $email_data);
        $html = $view->render();

        // Create the Transport
        $transport = (new \Swift_SmtpTransport(env('MAIL_HOST'), env('MAIL_PORT')))
            ->setUsername(env('MAIL_USERNAME'))
            ->setPassword(env('MAIL_PASSWORD'));

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message($subject))
            ->setFrom([env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME')])
            ->setTo([$toEmail])
            ->setBody($html, 'text/html');
        $result = $mailer->send($message);

        if ( $toSecondary ) {
            $message2 = (new \Swift_Message($subject))
                ->setFrom([env('MAIL_FROM_ADDRESS') => env('MAIL_FROM_NAME')])
                ->setTo($toSecondary)
                ->setBody($html, 'text/html');
            $result = $mailer->send($message2);
        }

        return $result;
    }
}

