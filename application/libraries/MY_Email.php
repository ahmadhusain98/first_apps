<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Email extends CI_Email
{
    private $CI;
    private $web_setting;

    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->CI->load->database();

        // Load the web setting once to avoid repeated DB calls
        $this->web_setting = $this->CI->db->query('SELECT * FROM web_setting WHERE id = 1')->row();
    }

    public function send_my_email($to, $subject, $message, $pdf)
    {
        if (!$this->web_setting) {
            // If the web setting is not found, return false or handle accordingly
            log_message('error', 'Web settings not found.');
            return false;
        }

        // Configure the email settings
        $config = [
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'protocol'      => 'smtp',
            'smtp_host'     => 'smtp.gmail.com',
            'smtp_user'     => $this->web_setting->email,
            'smtp_pass'     => $this->web_setting->kode_email,
            'smtp_crypto'   => 'ssl',
            'smtp_port'     => 465,
            'crlf'          => "\r\n",
            'newline'       => "\r\n"
        ];

        $this->clear();
        $this->initialize($config);

        // Set up email parameters
        $this->from($this->web_setting->email, $this->web_setting->nama);
        $this->to($to);
        $this->subject($subject);
        $this->message($message);
        $this->attach($pdf);

        // Try to send the email and return the result
        if ($this->send()) {
            return true;
        } else {
            // Log the error for troubleshooting
            log_message('error', 'Email sending failed: ' . $this->print_debugger());
            return false;
        }
    }
}
