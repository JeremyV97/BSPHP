<?php
ini_set('display_errors',1); error_reporting(E_ALL);

require("RequestHandler.php");
if(isset($_POST)){
    $json = file_get_contents('php://input');
    $request = json_decode($json, true);

    $rh = new RequestHandler();
    if(!isset($request["Request"])){
        header('HTTP/1.0 403 Forbidden');
    }else{
        $rh->handleRequest($request);
    }
}else{
    header('HTTP/1.0 403 Forbidden');
}
?>