<?php

ini_set('display_errors',1); error_reporting(E_ALL);


require("RequestHandler.php");
if(isset($_POST)){
    $json = file_get_contents('php://input');
    $request = json_decode($json, true);

    $rh = new RequestHandler();
    $rh->handleRequest($request["Request"]);
    
}else{
    echo "no";
    header('HTTP/1.0 403 Forbidden');
}



?>