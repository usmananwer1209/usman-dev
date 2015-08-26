<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Users_model extends Abstract_model {

    public function __construct() {
        parent::__construct();
        $this->table = 'user';
    }

    protected function set_object($data = array()) {
        foreach ($data as $index => $value) {
            if (strcmp($index, 'first_name') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'last_name') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'email') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'password') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'init_pass') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'is_active') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'is_root') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'organization') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'avatar') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'public_profile') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'country') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'about') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'twitter_profile') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'facebook_profile') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'google_profile') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'linkedin_profile') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'pass_token') == 0)
                $this->db->set($index, $value);
            elseif (strcmp($index, 'creation_time') == 0) {
                if (strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
            }
            elseif (strcmp($index, 'modification_time') == 0) {
                if (strcmp($value, 'now') == 0)
                    $this->db->set($index, 'NOW()', false);
                else
                    $this->db->set($index, $value);
            }
        }
    }

    public function get_by_email($email) {
        $this->db->distinct();
        $query = $this->db->get_where($this->table, array('email' => $email));
        $result = $query->result();
        if (!empty($result) && count($result) != 0)
            return $result[0];
        return null;
    }

    public function get_by_token($token) {
        $this->db->distinct();
        $query = $this->db->get_where($this->table, array('pass_token' => $token));
        $result = $query->result();
        if (!empty($result) && count($result) != 0)
            return $result[0];
        return null;
    }

    public function get_avatar($user) {
        if (!empty($user)) {
            $user_folder = uploads_url($user);
            $avatar_png = $user_folder . 'avatar.png';
            $avatar_jpg = $user_folder . 'avatar.jpg';
            $avatar_jpeg = $user_folder . 'avatar.jpeg';
            if (file_exists($avatar_png))
                return $avatar_png;
            elseif (file_exists($avatar_jpg))
                return $avatar_jpg;
            elseif (file_exists($avatar_jpeg))
                return $avatar_jpeg;
            else
                return uploads_url($user) . 'avatar.jpg';
        }
        else
            return uploads_url() . 'avatar.jpg';
    }

    public function list_records($circle_id = "") {
        if ($circle_id == "")
            return parent::list_records();
        else {
            $sql = "select u.* from user u inner join user_circle uc on uc.user = u.id where uc.circle=" . $circle_id;
            $query = $this->db->query($sql);
            return $query->result_array();
        }
    }

    public function list_records_and_admin($circle_id = "") {
        if ($circle_id == "")
            return parent::list_records();
        else {
            $sql = "(select u.* from user u, circle c where c.admin = u.id and c.id=".$circle_id.") union (select u.* from user u, user_circle uc where uc.user = u.id and uc.circle=".$circle_id." and uc.status = 2)" ;
            $query = $this->db->query($sql);
            return $query->result_array();
        }
    }

    public function list_cards($user_id) {
        $sql = "select c.* from card c  inner join user u on u.id = c.user where u.id =  $user_id ORDER BY c.creation_time DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function list_storyboards($user_id) {
        $sql = "select s.* from storyboard s inner join user u on u.id = s.user where u.id =  $user_id ORDER BY s.creation_time DESC";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
