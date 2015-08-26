<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

abstract class Abstract_model extends CI_Model{

	protected $table;
	protected $ids_name = array('id');
	protected $auto_increment = true;

	abstract protected function set_object($data = array());

	public function __construct(){
		parent::__construct();
		}

	private function get_simple_id($id){
		if($id){
			$where = array();
			if(count($this->ids_name)==1){
				$where[$this->ids_name[0]] = $id;
				}
			return $where;
			}
		}

	private function get_id($data){
		if(!empty($data)){
			$where = array();
			$data = ((object)$data);
			foreach ($this->ids_name as $id_name)
				$where[$id_name] = $data->$id_name;
			return $where; 
			}
		else 
			throw new Exception("Empty Object", 1);
		}

	protected function has_id($data){
		$data = (array)$data;
		$has_id = true;
		foreach ($this->ids_name as $id_name){
			if(!array_key_exists($id_name, $data) ){
				$has_id = false;
				break;
				}
			}
		return $has_id;
		}

	public function get_by_id($id){
		$_id = $this->get_simple_id($id);
		$query = $this->db->get_where($this->table, $_id);
		if($result = $query->result_array())
			return (object)$result[0];
		else 
			return null;
		}

	public function get_by_object($data){
		if(!empty($data)){
			$id = $this->get_id($data);
			$query = $this->db->get_where($this->table, $id);
			if($result = $query->result_array())
				return (object)$result[0];
			else 
				return null;
			}
		else 
			throw new Exception("Empty Object", 1);
		}

	public function save($data){
		if(!empty($data)){
			if($this->has_id($data))
				$obj = $this->get_by_object($data);	
			if(!empty($obj))
				return $this->edit($data);		
			else
				return  $this->add($data);
		}
	}

	public function add($data){
		$this->set_object((array)$data);
		$this->db->insert($this->table);
		if($this->auto_increment){
			$id = $this->db->insert_id();
			$_id = $this->get_simple_id($id);
			return (object)$this->get_by_id($id);	
		}
      else
            (object)($data);
		}

	public function edit($data){

		$id = $this->get_id($data);
		$this->set_object((array)$data);
		$this->db->where($id);
		$this->db->update($this->table);
		return (object)$data;
		}

	public function delete($data){
		$id = $this->get_id($data);
		return $this->db->where($id)->delete($this->table);
		}

	public function delete_list($where){
		return $this->db->where($where)->delete($this->table);
		}

	public function count($where = array()){
		return (int) $this->db->where($where)->count_all_results($this->table);
		}

  public function list_records($where = array(), $like = array(), $nb = 0, $start = 0, $order_by = array()) {
		$from = $this->db->select('*')->from($this->table);
		if(!empty($where))
			$from->where($where);
		if(!empty($like))
			$from->or_like($like);
		if($nb != 0)
			$this->db->limit($nb, $start);
		if(!empty($order_by[0]) && !empty($order_by[1]))
			$this->db->order_by($order_by[0], $order_by[1]);
		if(!empty($order_by[2]) && !empty($order_by[3]))
			$this->db->order_by($order_by[2], $order_by[3]);
		
		return $this->db->get()->result();
		}

	public function get_by_ids($ids) {
		$where = '';
		foreach ($ids as $k => $id) {
			if(empty($where))
				$where = 'id = '.$id;
			else
				$where .= ' OR id = '.$id;
		}
		$from = $this->db->select('*')->from($this->table);
		if(!empty($where))
			$from->where($where);
		return $this->db->get()->result();
	}

	/*
	public function list_records_sql($sql){
		$this->db->query($sql);
		return $query->result();		
	}	
	*/

	public function count_distinct($what, $where = array()){
		$this->db->select($what)->distinct()->from($this->table)->where($where);
		return $this->db->get()->num_rows();
		}

	public function get_data($data, $column){
		$id = $this->get_id($data);
		$query = $this->db->get_where($this->table, $id);
		foreach($query->result_array() as $row)
			return (object)$row[$column];
		}

	}
