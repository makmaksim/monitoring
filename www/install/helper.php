<?php
   
function get_sql($name, $params){
    extract($params);

    ob_start();
    require_once(dirname(__FILE__) . '/' . $name . '.php');
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

function removeDirectory($dir) {
    if ($objs = glob($dir."/*")) {
       foreach($objs as $obj) {
         if(!is_dir($obj)){
             unlink($obj);
         } 
       }
    }
    rmdir($dir);
}

function hashPassword($password, $key){
    $salt = md5(uniqid($key, true));
    $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
    return crypt($password, '$2a$08$' . $salt);
}