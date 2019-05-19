<?php

class RequestHandler{
    private $db;

    function __construct(){
        require("DatabaseHandler.php");
        $this->db = new DatabaseHandler();
    }

    public function handleRequest($Request){
        switch($Request['Request']){
            case "SQL":
                $this->handleQuery($Request);
                break;
            case "Login":
                $this->handleLogin($Request);
                break;
            case "SecureLogin":
                $this->handleSecureLogin($Request);
                break;
            case "RegisterKlant":
                $this->handleRegisterKlant($Request);
                break;
            case "RegisterBeheer":
                $this->handleRegisterBeheer($Request);
                break;
            default: 
                header('HTTP/1.0 403 Forbidden');
                break;
        }
        
    }

    private function handleQuery($Request){
        $Query = $Request["SQL"];
        $Resultaat = $this->db->request($Query);

        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }

    private function handleLogin($Request){
        $Resultaat = $this->db->requestLogin($Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["isGebruiker"]);
        
        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }

    private function handleSecureLogin($Request){
        $Resultaat = $this->db->requestSecureLogin($Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["isGebruiker"]);

        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }

    private function handleRegisterKlant($Request){
        $Resultaat = $this->db->requestRegisterKlant($Request["klantID"], $Request["Voornaam"], $Request["Achternaam"], $Request["Telefoon"], $Request["Email"], $Request["Adres"], $Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["Bedrijfsnaam"], $Request["isGoedgekeurd"]);

        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }

    private function handleRegisterBeheer($Request){
        $Resultaat = $this->db->requestRegisterBeheer($Request["Gebruikersnaam"], $Request["Wachtwoord"]);

        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }
}

?>