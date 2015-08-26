<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 5/8/2015
 * Time: 2:57 PM
 */

class maintenance extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($message = "") {

        $get_data = $this->input->get();

        if ($get_data['letmein'] == 'true') {

            redirect('/login');

        }
        else {
            $this->load->view('signing/maintenance');
        }
    }

}