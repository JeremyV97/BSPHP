<?php
if(isset($_POST["Request"])){
    $rh = new RequestHandler();
    $rh->handleRequest($_POST["Request"]);
}else{
    header('HTTP/1.0 403 Forbidden');
}



?>