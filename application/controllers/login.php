<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    protected $page_data;

    public function __construct() {
        parent::__construct();

        $this->benchmark->mark('LoginController-Constructor_start');

        $this->load->model('users_model', 'users');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
        $this->page_data = array();

        $this->benchmark->mark('LoginController-Constructor_end');
    }

    public function index($message = "") {

        $this->benchmark->mark('LoginController-index_start');

        $user = $this->session->userdata('user');
        //if (!empty($user)) {
        //redirect('home');
        //}
        //else {
        $this->page_data['title'] = 'Login ' . app_name();
        $this->page_data['current'] = 'login';
        $this->page_data['message'] = $message;
        $this->page_data['description'] = 'Login to your account';
        $this->load->helper(array('form'));
        $this->load->view('signing/login', $this->page_data);
        //}

        $this->benchmark->mark('LoginController-index_end');
    }

    public function excuteQuery() {

        $this->benchmark->mark('LoginController-executeQuery_start');

        //$sql = 'ALTER TABLE card ADD public tinyint(1) DEFAULT "0" AFTER modification_time ;';
        //$this->db->query($sql);

        $sql = "select * from card";
        $query = $this->db->query($sql);

        $result = $query->result();
        echo '<pre>';
        var_dump($result);
        echo '</pre>';

        $this->benchmark->mark('LoginController-executeQuery_end');
    }

    public function verifylogin() {

        $this->benchmark->mark('LoginController-verifyLogin_start');

        $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[2]|max_length[100]|encode_php_tags|xss_clean|callback_check_email|callback_check_active');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[5]|max_length[100]|encode_php_tags|xss_clean|callback_check_password');
        if ($this->form_validation->run()) {
            $user = $this->users->get_by_email(trim($this->input->post('email')));
            if (empty($user) || count($user) == 0) {
                $message = $invalid_password;
                $this->index($message);
            } else if ($user->is_active != "1") {
                $message = account_inactive();
                $this->index($message);
            } else {
                $this->session->set_userdata('user', $user);
                redirect('/home');
            }
        } else {
            $this->index();
        }

        $this->benchmark->mark('LoginController-verifyLogin_end');

    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function check_email($email) {
        $user = $this->users->get_by_email($email);
        if (!empty($user->id))
            return true;
        else {
            $this->form_validation->set_message('check_email', invalid_email());
            return false;
        }
    }

    public function check_active($email) {
        $user = $this->users->get_by_email($email);
        if (!empty($user->id) && $user->is_active == "1")
            return true;
        else {
            $this->form_validation->set_message('check_active', account_inactive());
            return false;
        }
    }

    public function check_password($password) {
        $user = $this->users->get_by_email(trim($this->input->post('email')));
        if (!empty($user->id)) {
            $db_pass = $this->users->get_data($user, 'password');
            if (strcmp($password, $db_pass->scalar) == 0)
                return true;
        }
        $this->form_validation->set_message('check_password', invalid_password());
        return false;
    }

    // reset password begin

    public function reset_password($message = "") {
        $this->page_data['title'] = 'Reset password ' . app_name();
        $this->page_data['current'] = 'reset_password';
        $this->page_data['message'] = $message;
        $this->page_data['description'] = 'Reset your password';
        //  $this->load->helper(array('form'));
        $this->load->view('signing/reset_password', $this->page_data);
    }

    public function reset_pass($token = "") {
        $token = $this->input->get('token');
        $user = $this->users->get_by_token($token);
        if (empty($user) || count($user) == 0) {
            $message = "The url you followed isn't a valid url.<br/>Try again";
            $this->reset_password($message);
        } else {

            $this->page_data['title'] = 'Reset password ' . app_name();
            $this->page_data['current'] = 'reset_password';
            $this->page_data['description'] = 'Reset your password';
            $this->page_data['token'] = $token;
            $this->load->view('signing/reset_pass', $this->page_data);
        }
    }

    public function reset_pass_submit() {
        $this->form_validation->set_rules('password', 'password', 'trim|min_length[5]|max_length[50]|alpha_dash|encode_php_tags|xss_clean');
        $this->form_validation->set_rules('repassword', 'confirm password', 'trim|callback_required_for[' . $this->input->post('password') . ']|matches[password]|encode_php_tags|xss_clean');

        if ($this->form_validation->run()) {

            $user = $this->users->get_by_token(trim($this->input->post('token')));
            if (empty($user) || count($user) == 0) {
                $message = "The url you followed isn't a valid url.<br/>Try again";
                $this->reset_password($message);
            } else {

                $user->pass_token = "";
                $user->password = $this->input->post('password');

                $user = $this->users->save($user);

                $this->index("Your password was successfully edited.<br/>Try to login now");
            }
        } else {
            $this->reset_pass();
        }
    }

    public function rp_submit() {
        $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[2]|max_length[100]|encode_php_tags|xss_clean|callback_check_email|callback_check_active');
        if ($this->form_validation->run()) {
            $user = $this->users->get_by_email(trim($this->input->post('email')));
            if (empty($user) || count($user) == 0) {
                $message = "The email doesn't exist";
                $this->reset_password($message);
            } else {
                $token = uniqid();

                $user->pass_token = $token;

                $user = $this->users->save($user);
                $this->send_mail($token, $user->email);

                $this->reset_password("An email was sent to your email address. <br/>Please follow the link to reset your password");
            }
        } else {
            $this->reset_password();
        }
    }

    public function send_mail($token, $to) {
        $config['mailtype'] = 'html';
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->from($this->config->item('from'), $this->config->item('from_name'));
        $this->email->to($to);

        $this->email->subject(app_name() . " Reset password");

        
        $email_message = "Someone recently requested a link to make password change for your idaciti account. If this was you, you can set a new password through the link below.<br/><br/><br/>";
        $email_message .= '<a href="' . site_url('/login/reset_pass?token=' . $token) . '">' . site_url('/login/reset_pass?token=' . $token) . '</a>';
        $email_message .= "<br/><br/><br/>If you don't want to change your password or didn't request this, just ignore and delete this message.<br/><br/><br/>Thanks,<br/>Your idaciti Team ";

        $this->email->message($email_message);

        if (!$this->email->send()) {
            log_message('error', 'Email Failed. Debug: ' . $this->email->print_debugger() . " \r\n");
            return false;
        }

        return true;
    }

    // reset password end
}
