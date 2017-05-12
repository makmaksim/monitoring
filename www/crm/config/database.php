<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => 'mysql:host=localhost;dbname=crm_db',
    'hostname' => 'localhost',
    'username' => 'crm_user',
    'password' => 'crm_password',
    'database' => 'crm_db',
    'dbdriver' => 'pdo',
    'dbprefix' => 'leb_',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '{PRE}',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
    'md5_key' => 'lkj'
);