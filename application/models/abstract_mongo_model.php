<?php

//require_once '..\libraries\Mongo_db.php';

abstract class Abstract_mongo_model extends CI_Model {
	
    protected $collection;

    abstract protected function set_object($data = array());

    protected $ids_name = array('entityId', '_id');

    public function __construct(){
		
        $this->load->library('mongo_db');

        parent::__construct();
    }

    public function find($like = array(), $order_by = array()) {

        return $this->list_records($like, $order_by);
    }

    public function list_records($like = array(), $order_by = array()) {

        if (!isset($this->collection)) {

            log_message('error', 'collection must be set');
            show_error('collection must be set');
            return;
        }
        else {
            log_message('debug', 'collection is set');
        }

        try {
            // where provides exact match, like provides like functionality
            $mCursor = $this->collection->find($like);
            log_message('debug', 'back from find');

            if (isset($order_by)) {
                if (is_array($order_by) && count($order_by) > 0) {
                    log_message('debug', 'going to sort');
                    $mCursor->sort($order_by);
                }
                else {
                    //log_message('error', '$order_by is set but not an array: ' . $order_by);
                }
            }

            if ($mCursor->hasNext()) {

                $arr = iterator_to_array($mCursor, FALSE);

                log_message('debug', 'mongo find returning array');
            }
            else {
                $arr = array();
                log_message('error', 'mongo cursor is invalid: ' . print_r($mCursor->info(), true));
            }
            return $arr;
        }
        catch (Exception $e) {
            log_message('error', 'mongo list_records: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    protected function has_id($data) {

        $data = (array)$data;

        foreach ($this->ids_name as $id_name) {
            if (!array_key_exists($id_name, $data)) {
                return false;
            }
        }
        return true;
    }

    private function get_id($data) {

        $idname = $this->ids_name[0];

        if (isset($data[$idname])) {
            return $data[$idname];
        }

        return 0;
    }

    public function save($data) {

        if(!empty($data)) {

            $objData = $this->set_object($data);

            $entityId = $this->get_id($data);

            return $this->save_collection([$this->ids_name[0] => $entityId], $objData);
        }
    }

    public function bulkInsert($data) {

        if(!empty($data)) {

            $objData = $this->set_object($data);
			
            return $this->collection->batchInsert($objData, ['continueOnError' => true]);
        }
    }

    public function remove($data) {

        if (!empty($data)) {

            $delArray = $this->get_delete_keys($data);

            $res = $this->collection->remove($delArray);

            return $res;
        }
    }

    /*
    public function get_last_id() {

        $mCursor = $this->collection->find()->sort(['EntityId' => 1])->limit(1);

        $mCursor->next();
        return $mCursor->current()->EntityId;
    }
    */

    protected function save_collection($findCrit, $data) {

        return $this->collection->update($findCrit, $data, ['upsert' => true]);
    }

    public function aggregate($pipeline) {

        if (!isset($this->collection)) {

            show_error('collection must be set');
            return;
        }

        return $this->collection->aggregate($pipeline);
    }

}