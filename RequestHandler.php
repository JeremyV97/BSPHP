<?php
class RequestHandler{

    $neppeQuery = "select Wachtwoord from Beheer where Gebruikersnaam = 'admin'";
    $db;

    function __construct(){
        $db = new DatabaseHandler();
    }

    function handleRequest($Request){
        $Query = $Request[0];
        $Resultaat = $db->request($Query);

        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }

}

?>