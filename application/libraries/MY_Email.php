<?php

defined('BASEPATH') or exit('No direct script access allowed');


class MY_Email extends CI_Email
{
    private $CI;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->database();
    }

    public function send_my_email($to, $subject, $message, $pdf)
    {
        $CI = &get_instance();

        $web_setting = $CI->db->query('SELECT * FROM web_setting WHERE id = 1')->row();

        $config = [
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'protocol'      => 'smtp',
            'smtp_host'     => 'smtp.gmail.com',
            'smtp_user'     => $web_setting->email,
            'smtp_pass'     => $web_setting->kode_email,
            'smtp_crypto'   => 'ssl',
            'smtp_port'     => 465,
            'crlf'          => "\r\n",
            'newline'       => "\r\n"
        ];

        $this->clear();
        $this->initialize($config);
        $this->from($web_setting->email, $web_setting->nama);
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
