<?php

class RequestHandler{
    private $db;

    function __construct(){
        require("DatabaseHandler.php");
        $this->db = new DatabaseHandler();
    }

    function handleRequest($Request){
        $Query = $Request;
        $Resultaat = $this->db->request($Query);

        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }
}

?>