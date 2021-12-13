<?php

namespace App\Services;

use App\Email;

class EmailPatternService {

    const TEMPLATE = 'emails.agent_patterns';

    private $config;

    public function __construct()
    {
        $this->config = config('emailpattern');
    }

    public function sendEmailToAgentAndAdmin($event, $model, $fields )
    {
        return (
            $this->sendEmailToAgent($event, $model, $fields)
            &&
            $this->sendEmailToAdmin($event, $model, $fields)
        );
    }

    public function sendEmailToAgent($event, $model, $fields)
    {

        $email = Email::where('event', $event)->first();

        if ( !empty($fields['link']) )
            unset($fields['link']);

        if ( $email->is_active ) {

            $data = $this->getPatternData($event, $model, $fields);
            $order = $model->name ? $model : false;
            $agent = $model->agent ?? $model->order->agent;

            return $this->sendPatternEmail($agent, $data['subject_agent'], nl2br($data['body_agent']), self::TEMPLATE, $order);
        }

        return false;
    }

    public function sendEmailToAdmin($event, $model, $fields)
    {
        $email = Email::where('event', $event)->first();

        if ( $email->is_active ) {
            $data = $this->getPatternData($event, $model, $fields);
            $order = $model->name ? $model : false;

            return $this->sendPatternEmail(env('MAIL_ADMIN_ADDRESS'), $data['subject_admin'], nl2br($data['body_admin']), self::TEMPLATE, $order);
        }

        return false;
    }

    private function getPatternData($event, $model, $fields)
    {
        $subject_agent  = $this->getPattern('subject_agent', $event, $model, $fields);
        $subject_admin  = $this->getPattern('subject_admin', $event, $model, $fields);
        $body_agent     = $this->getPattern('body_agent', $event, $model, $fields);
        $body_admin     = $this->getPattern('body_admin', $event, $model, $fields);

        return compact('subject_agent', 'subject_admin', 'body_agent', 'body_admin');
    }

    private function getPattern($pattern, $event, $model, $fields)
    {
        $global_data_pattern = $this->setGlobalRequestDataPattern($model, $fields);

        if ( in_array($pattern, ['subject_agent', 'body_agent']))
            unset($global_data_pattern['link']);

        $local_data_pattern = [];

        foreach ($fields as $df_key => $df_val) {
            $key = '['.str_slug(strtolower($df_key), '_').']';
            $local_data_pattern[$key] = $df_val;
        }
        if ( $model->name ) {
            unset($this->config['global']['[task_name]']);
            unset($this->config['global']['[public_fields]']);
            unset($this->config['global']['[private_fields]']);
            unset($this->config['global']['[status]']);

        }
        $vars = array_merge($this->config['global'], $this->config[$event]);
        $data = array_merge($vars, $global_data_pattern, $local_data_pattern);

        //Apply all data to pattern
        $email = Email::where('event', $event)->first();

        $pattern_result = str_replace(array_keys($vars), $data, $email->{$pattern});

        return $pattern_result;
    }

    private function setGlobalRequestDataPattern($model, $fields)
    {
        $data = [
            '[agent_name]' => '',
            '[agent_email]' => '',
            '[admin_email]' => '',
            '[link]' => '',
            '[task_name]' => '',
            '[public_fields]' => '',
            '[private_fields]' => '',
            '[status]' => '',
        ];

        $data['[agent_name]'] = $model->agent ? $model->agent->name : $model->order->agent->name;
        $data['[agent_email]'] = $model->agent ? $model->agent->email : $model->order->agent->email;
        $data['[admin_email]'] = env('MAIL_ADMIN_ADDRESS');

        $data_fields = $fields;

        //If model is order (listing)
        if ( $model->name ) {
            $data['[address]'] = $model->name;
            $data['[listing_fields]'] = $data_fields['Listing Fields'];
            $data['[link]'] = '<a href="'. route('dashboard.edit', $model->id).'"><span class="text-primary">'.route('dashboard.edit', $model->id).'</span></a>';

            unset($data['[task_name]']);
            unset($data['[public_fields]']);
            unset($data['[private_fields]']);
            unset($data['[status]']);
        } else {
            //If model is order request
            $data['[task_name]'] = $model->order ? $model->order->name : $model->custom_name;

            $data_fields['Address'] = $model->order ? $model->order->name : 'unassigned';
            $data_fields['Task'] = $model->subsection ? $model->subsection->name : $model->custom_name;
            $data_fields['Status'] = $model->status;
            $data_fields['Public Notes'] = $model->public_notes;
            $data_fields['Private Notes'] = $model->private_notes;
            $data_fields['link'] = route('dashboard.index').'#'.$model->id;
            $data['[private_fields]'] = $this->formDataToHTML($data_fields);

            //For agent
            unset($data_fields['Private Notes']);
            unset($data_fields['link']);
            $data['[public_fields]'] = $this->formDataToHTML($data_fields);
            $data['[status]'] = $model->status;
            $data['link'] = route('dashboard.index').'#'.$model->id;
        }

        return $data;
    }

    private function formDataToHTML($fields)
    {
        $html = '';

        foreach ($fields as $df_name => $df_value) {
            $html .= '<p>';
            if( !empty($df_value) ) {
                $html .= $df_name . ' : ';
                if (strpos($df_value, 'http') !== FALSE) {
                    $html .= '<a href="'.$df_value.'" class="text-primary">'.$df_value.'</a>';
                } else {
                    $html .= '<span class="text-primary">'.$df_value.'</span>';
                }
            }
            $html .= '</p>';
        }

        return $html;
    }

    public static function listingDataToHTML($fields)
    {
        $html = '<div>';

        foreach ($fields as $section_name => $subsections) {
            $html .= '<h4>'.$section_name.'</h4>';
            foreach ($subsections as $subsection_name => $sub_fields) {
                $html .= '<h5>'.$subsection_name.'</h5>';
                foreach ($sub_fields as $sub_field) {
                    $html .= '<p>';
                    if( !empty($sub_field['name']) ) {
                        $html .= $sub_field['name'].':';
                        $html .= ' <span class="text-primary">'.$sub_field['value'].'</span>';
                    }
                }
            }
        }
        $html .= '</div>';
        return $html;
    }

    public static function sendPatternEmail($to, $subject, $body, $template, $model = false) {

        if(is_object($to)) {
            $toEmail = trim($to->email);
            $toSecondary = $to->secondary_email ? $to->secondary_email : false;
        } else {
            $toEmail = trim($to);
            $toSecondary = false;
        }

        $email_data = [
            'subject' => $subject,
            'body' => $body
        ];
        if ( $model ) {
            $email_data['order'] = $model;
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
}
