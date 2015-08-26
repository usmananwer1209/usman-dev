<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mongo_db {

    protected $mClient;
    protected $mdb;
    protected $mConfigData;

    public function __construct()
    {

        if (!class_exists('Mongo')) {
            $this->_show_error('The MongoDB PECL extension has not been installed or enabled', 500);
        }

        //get instance of CI class
        if (function_exists('get_instance')) {
            $this->_ci = get_instance();
        }
        else {
            $this->_ci = NULL;
        }


        //load the config file which we have created in 'config' directory
        $this->_ci->load->config('mongodb');
        $config = 'mongo';

        //Fetch Mongo server and database configuration from config file which we have created in 'config' directory
        $this->mConfigData = $this->_ci->config->item($config);

        try {

            //connect to the mongodb server
            $this->mClient = $this->get_mongo_client();

            //select the mongodb database
            //$this->db = $this->select_database($config_data['mongo_database']);

        } catch (MongoConnectionException $exception) {

            //if mongodb is not connect, then display the error
            show_error('Unable to connect to Database', 500);
        }
    }

    public function get_mongo_client($retry = 10) {

        log_message('debug', "attempt to get mongo client: retry #" . (10-$retry+1));

        try {
            if ($this->mConfigData['username'] !== "") {
                $connStr = $this->mConfigData['dbdriver'] . '://' . $this->mConfigData['username'] . ':' . $this->mConfigData['password'] . '@' . $this->mConfigData['hostname'] . '/' . $this->mConfigData['database'];
            }
            else {
                $connStr = $this->mConfigData['dbdriver'] . '://' . $this->mConfigData['hostname'] . '/' . $this->mConfigData['database'];
            }

            return new MongoClient($connStr);
            //return new MongoClient('mongodb://idacitiUI:idaciti2014@174.36.13.179:27017/idaciti');
        }
        catch (Exception $e) {
            if ($retry > 0) {
                return $this->get_mongo_client(--$retry);
            }
        }
    }

    public function set_database($db_key_name)
    {
        return $this->mdb = $this->mClient->selectDB($this->mConfigData[$db_key_name]);
    }

    public function get_collection($collection_key_name) {

        return $this->mdb->selectCollection($this->mConfigData[$collection_key_name]);
    }

    public function close() {

        $this->mClient->close();
    }
}