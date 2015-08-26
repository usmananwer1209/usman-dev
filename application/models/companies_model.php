<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'abstract_model.php';

class Companies_model extends Abstract_model{

	public function __construct(){
		parent::__construct();
		$this->table = 'company';
		$this->ids_name = array('entity_id');
		$this->auto_increment = false;
		}
	  
	protected function set_object($data = array()){
		foreach($data as $index => $value){
			if(strcmp($index, 'entity_id') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'cik') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'company_name') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'industry') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'sector') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'sic') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'sic_code') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'state') == 0)
				$this->db->set($index,  $value);
			elseif(strcmp($index, 'stock_symbol') == 0)
				$this->db->set($index,  $value);
			}
		}
	public function delete_all(){
		$sql = "delete from company";
		$query = $this->db->query($sql);		
	}

	public function get_sector_by_name(){
		$sector_name = $this->uri->segment(3);
		$sector_name = str_replace('%20',' ',$sector_name);
		$this->db->where('sector', $sector_name);
		$this->db->select('sector')->from($this->table)->group_by("sector");
		return $this->db->get()->result();
	}
	public function get_sector(){
		$this->db->select('sector')->from($this->table)->group_by("sector");
		return $this->db->get()->result();
	}
	public function get_sector_by_company_name(){
		$company_name = $this->uri->segment(3);
		$company_name = str_replace('%20',' ',$company_name);
		$this->db->like('company_name', $company_name, 'both');
		$this->db->or_like('stock_symbol', $company_name, 'both'); 
		$this->db->select('entity_id, company_name, industry, sector, sic')->from($this->table)->group_by("sector")->order_by("company_name", "asc")->limit(1);
		return $this->db->get()->result();
	}
    public function get_sic($industry) {
        $this->db->select('sic')->from($this->table)->where(array("industry" => $industry))->group_by("sic");
        return $this->db->get()->result();
    }
    public function get_companies_from_sic($sic) {
        //$this->db->select('company_name, count(entity_id) as count')->from($this->table)->where(array("sic" => $sic));
        $this->db->select('company_name, (select 1 from dual) as count')->from($this->table)->where(array("sic" => $sic));
        return $this->db->get()->result();
    }
	public function get_industry_and_count($sector){
		$this->db->select('industry,count(entity_id) as count')->from($this->table)->where(array("sector"=>$sector))->group_by("sector")->group_by("industry");
		return $this->db->get()->result();
		}
	public function get_sics_and_count($industry){
		$this->db->select('sic_code, sic,count(entity_id) as count')->from($this->table)->where(array("industry"=>$industry))->group_by("industry")->group_by("sic");
		return $this->db->get()->result();
		}
	public function get_all_sics_and_count(){
		$this->db->select('sic_code, sic,count(entity_id) as count')->from($this->table)->group_by("industry")->group_by("sic");
        return $this->db->get()->result();
    }
	public function get_companies($industry){
		$this->db->select('company_name')->from($this->table)->where(array("industry"=>$industry));
		return $this->db->get()->result();
		}
	public function get_companies_by_sic_code($sic_code){
		$this->db->select('entity_id, company_name')->from($this->table)->where(array("sic_code"=>$sic_code))->order_by("company_name", "asc");
;
        return $this->db->get()->result();
    }

  public function get_companies_by_ids($ids){
    $where = '';
    if(!empty($ids) && is_array($ids))
    {
      foreach ($ids as $id) {
        if($where == '')
          $where .= "`entity_id` = '".$id."'";
        else
          $where .= " OR `entity_id` = '".$id."'";
      }
    
      $this->db->select('*')->from($this->table)->where($where);
      return $this->db->get()->result();
    }
    else
      return false;
  }

}
