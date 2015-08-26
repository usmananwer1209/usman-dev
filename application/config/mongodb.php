<?php

/* 180 credentials 
$config['mongo']['hostname'] = '174.36.13.180:27017';
$config['mongo']['username'] = ''; //'idaciti';
$config['mongo']['password'] = 'idacitiUI'; //2014';
$config['mongo']['database'] = 'idaciti';
$config['mongo']['dbdriver'] = 'mongodb';
*/

/* 179 creds */
$config['mongo']['hostname'] = '174.36.13.179:27017';
$config['mongo']['username'] = '';
$config['mongo']['password'] = ''; 
$config['mongo']['database'] = 'idaciti';
$config['mongo']['dbdriver'] = 'mongodb';
/* */

// collections
$config['mongo']['privateCompanies_Col'] = 'PrivateOrganizations';
$config['mongo']['TermResults_Col'] = 'TermResults';
$config['mongo']['termBenchmark_Col'] = 'TermBenchmark';
$config['mongo']['benchmarkSaaSData_Col'] = 'BenchmarkSaaSData';
$config['mongo']['contractFacts_Col'] = 'ContractFacts';
$config['mongo']['insiderTradingFacts_Col'] = 'InsiderTradingFacts';
