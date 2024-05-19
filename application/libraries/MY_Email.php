<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Email extends CI_Email
{
    private $config = [
        'mailtype'      => 'html',
        'charset'       => 'utf-8',
        'protocol'      => 'smtp',
        'smtp_host'     => 'smtp.gmail.com',
        'smtp_user'     => 'myhers.official@gmail.com',
        'smtp_pass'     => 'gkgf yxav gone uqon',
        'smtp_crypto'   => 'ssl',
        'smtp_port'     => 465,
        'crlf'          => "\r\n",
        'newline'       => "\r\n"
    ];

    public function __construct()
    {
        parent::__construct($this->config);
    }

    public function send_my_email($to, $subject, $message, $pdf)
    {
        $this->clear();
        $this->initialize($this->config);
        $this->from('myhers.official@gmail.com', 'MY HERS');
        $this->to($to);
        $this->subject($subject);
        $this->message($message);
        $this->attach($pdf);

        if ($this->send()) {
            return true;
        } else {
            return false;
        }
    }
}
