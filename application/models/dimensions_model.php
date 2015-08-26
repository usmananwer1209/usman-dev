<?php

/*
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// leaving this
require_once 'abstract_model.php';
*/

require_once 'abstract_mongo_model.php';

class dimensions_model extends Abstract_mongo_model {

    public function __construct(){
        parent::__construct();

        $this->mongo_db->set_database('database');
        $this->collection = $this->mongo_db->get_collection('TermResults_Col');
    }

    protected function set_object($data = array()) {

        // NO OP
        return $data;
    }

    /* not used anymore due to Abstract_Mongo_model
    protected function get_mongo_client($retry = 10) {

        $temp = $this->config->item('mongo_dbdriver');

        try {
            return new MongoClient($this->config->item('mongo_dbdriver').'://'.$this->config->item('mongo_username').':'.$this->config->item('mongo_password').'@'.$this->config->item('mongo_hostname') . '/' . $this->config->item('mongo_database'));
            //return new MongoClient('mongodb://idacitiUI:idaciti2014@174.36.13.179:27017/idaciti');
        }
        catch (Exception $e) {
            if ($retry > 0) {
                return $this->get_mongo_client(--$retry);
            }
        }
    }
    */

    public function get_drilldown($entityId, $termId, $year_start, $year_end, $fiscal_type) {

        /*
db.TermResults.aggregate(
    // Initial document match (uses index, if a suitable one is available)
    { $match: {
        entityId: { $in : ['000055', '002178']},
        termId : '314019',
        FY: 2013,
        FQ : 'FY'

    }},

    // Expand the dimensionalFacts array into a stream of documents
    { $unwind: '$dimensionalFacts' },

    // Sort in descending order
    { $sort: {
        'companyName' : 1,
        'dimensionalFacts.axes': 1,
        'FY' : 1,
        'FQ' : 1,
        'dimensionalFacts.value': 1
    }}
)
         */

        //$mongo = $this->get_mongo_client();

        // select a database
        //$mdb = $mongo->selectDB($this->config->item('mongo_database'));

        // select a collection (analogous to a relational database's table)
        //$termResults = $mdb->selectCollection($this->config->item('mongo_collection'));

        //$mdb = $mongo->selectDB('idaciti');
        //$termResults = $mdb->selectCollection('TermResults');

        $entityId = str_replace(' ', '', $entityId);
        $entities = explode(',', $entityId);

        $pipeline = [
            [
                '$match' => [
                    'entityId' => [ '$in' => $entities ],
                    'termId' => $termId
                ]
            ],
            [
                '$unwind' => '$dimensionalFacts'
            ],
            [
                '$sort' => [
                    'companyName' => 1,
                    'dimensionalFacts.axes' => 1,
                    // 'FY' => 1,
                    // 'FQ' => 1,
                    'dimensionalFacts.value' => 1
                ]
            ]
        ];

        if ($year_end) {
            $pipeline[0]['$match']['FY'] = array();

            $pipeline[0]['$match']['FY']['$gte'] = intval($year_start);
            $pipeline[0]['$match']['FY']['$lte'] = intval($year_end);
        }
        else {
            $pipeline[0]['$match']['FY'] = intval($year_start);
        }

        if ($fiscal_type) {
            $pipeline[0]['$match']['FQ'] = $fiscal_type;
        }

        // find everything in the collection
        //log_message('debug', 'mem usage before aggregate: ' . memory_get_usage(true));
        $cursor = $this->aggregate($pipeline);
        //log_message('debug', 'mem usage after aggregate: ' . memory_get_usage(true));

        if ($cursor['ok']) {

            //log_message('debug', 'mem usage before process_results: ' . memory_get_usage(true));

            $data = $this->process_results($cursor['result']);

            //log_message('debug', 'mem usage after process_results: ' . memory_get_usage(true));

            $this->mongo_db->close();

            //log_message('debug', 'mem usage after close: ' . memory_get_usage(true));

            return $data;
        }
        else {
            log_message('error', 'get_dd cursor is not ok');
        }
    }

    public function process_results($results) {

        $parents = array();

        $wrapper = new stdClass();
        $wrapper->numDimensions = 0;
        $wrapper->parent = new stdClass();

        $first = TRUE;
        foreach ($results as $resultsIndex => $row) {

            if ($first == TRUE) {

                // this shouldn't matter too much - we're always getting the same termName back
                //$currentTermName = $row->termName;

                // root
                $wrapper->parent->companyName = 'root';

                //$parent->elementName = 'root';
                $wrapper->parent->name = $row['termName'] . ', ' . $row['FY'];

                if ($row['FQ'] != 'FY') {
                    $wrapper->parent->name .= $row['FQ'];
                }

                $wrapper->parent->value = 0.0;
                //$parent->FY = $row['FY'];

                $wrapper->parent->children = array();

                $parents[] = $wrapper->parent;

                $first = FALSE;
            }

            // there should only be 1 of these
            //foreach ($row['dimensionalFacts'] as $dimFactIndex => $dimFact) {

                $dimFact = $row['dimensionalFacts'];
                $tempParent = $this->find_parent($parents, $row['companyName'],
                        $dimFact['axes'],
                        $dimFact['axesLabel'] );

                $nameToUse = '';
                foreach ($dimFact['dimensions'] as $dimIndex => $dim) {
                    $nameToUse .= $dim['memberLabel'] . ', ';
                }

                // add the child
                // and now process the dimensions
                $childObj = new stdClass();

                // remove the trailing comma
                $childObj->name = rtrim($nameToUse, ', ');
                $childObj->value = $dimFact['value'];

                if (!$tempParent->children) {
                    $tempParent->children = array();
                }

                // add to the bottom
                $tempParent->children[] = $childObj;


                // add the values to the parents;
                foreach ($parents as $ap) {
                    $ap->value += $dimFact['value'];
                }

                $wrapper->numDimensions += count($tempParent->children);
            //}
        }

        return $wrapper;
    }

    function find_parent(&$allParents, $companyName, /*$elementName,*/ $axesElementName, /*, $year,*/ $axesLabel /*, $termVal*/)
    {
        $curParent = array_pop($allParents);

        // first check for company name
        if ($curParent->companyName != $companyName) {

            // unwind all the parents
            if ($curParent->companyName != 'root') {
                do {
                    $someParent = array_pop($allParents);
                } while ($someParent->companyName != 'root');
            }
            else {
                $someParent = $curParent;
            }
            // put root back
            $grandpa = $allParents[] = $someParent;

            //$nameToUse = $companyName;// . ', ' . $year;

            // skip the axes on purpose - need to creat a new company first
            $this->create_parent($allParents, $grandpa, $companyName, $companyName);

            // and try this again
            return $this->find_parent($allParents, $companyName, $axesElementName, $axesLabel);
        }
/*
        else if ($curParent->year != $year) {

            // next parent up is the company, which is the one we want
            $grandpa = array_pop($allParents);

            $nameToUse = $year;
        }
*/
        else if (!$curParent->axesElementName || ($curParent->axesElementName != $axesElementName)) {

            // if we get here, the company is the same but we have a new axes
            if ($curParent->axesElementName) {
                // we have a dimension, but it's the wrong one

                // so pop off the stack until we get back to the company
                do {
                    $curParent = array_pop($allParents);
                } while ($curParent->axesElementName);

                // and put company back onto the stack
                $allParents[] = $curParent;
            }
            else {
                // no dimension, we're sitting on the company, put it back
                $allParents[] = $curParent;
            }


            return $this->create_parent($allParents, $curParent, str_replace('|', ', ', $axesLabel), $companyName, $axesElementName);
        }
        else {
            // everything matches, put the curParent back on and return
            $allParents[] = $curParent;

            return $curParent;
        }

        // should never get here

        //$allParents[0]->value += $termVal;
        // push grandpa and child onto the parent stack
        //$allParents[] = $grandpa;

        //return $curParent;
    }

    function create_parent(&$allParents, &$grandpa, $nameToUse, $companyName, $axesElementName=null ) {

        $newParent = new stdClass();

        //$curParent->elementName = $elementName;
        $newParent->axesElementName = $axesElementName;

        $newParent->companyName = $companyName;
        $newParent->name = $nameToUse;
        $newParent->value = 0; //$termVal;
        //$curParent->year = $year;
        $newParent->children = array();

        $grandpa->children[] = $newParent;

        $allParents[] = $newParent;

        return $newParent;
    }
}