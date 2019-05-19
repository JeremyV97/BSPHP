<?php

class RequestHandler{
    private $db;

    function __construct(){
        require("DatabaseHandler.php");
        $this->db = new DatabaseHandler();
    }

    public function handleRequest($Request){
        $Query = "";
        $Resultaat = "";

        switch($Request['Request']){
            case "SQL":
                $Query = $Request["SQL"];
                $Resultaat = $this->db->request($Query);
                break;
            case "Login":
                $Resultaat = $this->db->requestLogin($Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["isGebruiker"]);
                break;
            case "SecureLogin":
            $Resultaat = $this->db->requestSecureLogin($Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["isGebruiker"]);
                break;
            case "RegisterKlant":
            $Resultaat = $this->db->requestRegisterKlant($Request["klantID"], $Request["Voornaam"], $Request["Achternaam"], $Request["Telefoon"], $Request["Email"], $Request["Adres"], $Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["Bedrijfsnaam"], $Request["isGoedgekeurd"]);
                break;
            case "RegisterBeheer":
                $Resultaat = $this->db->requestRegisterBeheer($Request["Gebruikersnaam"], $Request["Wachtwoord"]);
                break;
            case "PasswordChange":
                $Resultaat = $this->db->requestPasswordUpdate($Request["Gebruikersnaam"], $Request["Wachtwoord"], $Request["isGebruiker"]);
                break;
            default: 
                header('HTTP/1.0 403 Forbidden');
                die();
                break;
        }
        header('Content-Type: application/json');
        echo json_encode($Resultaat);
    }

}

?>