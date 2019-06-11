<?php

/**
 * Class: RequestHandler
 * @author Jeremy Vorrink
 * Description: Handled de request die binnen komt wanneer de webservice word aangeroepen
 */
class RequestHandler{
    private $db;

    /**
     * Function: constructir
     * @author Jeremy Vorrink
     * Description: Initializeert de class
     */
    function __construct(){
        require("DatabaseHandler.php");
        $this->db = new DatabaseHandler();
    }

    /**
     * Function: handleRequest
     * @param $Request
     * @param $Silent = false
     * @author Jeremy Vorrink
     * Description: leest de request uit en stuurt de request door naar database handler
     */
    public function handleRequest($Request, $Silent = false){
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
            case "RegisterRekening":
                $Resultaat = $this->db->requestRegisterRekening($Request["Rekeningnummer"], $Request["RekeningsoortSoort"], $Request["Rente"], $Request["Saldo"]);
                break;
            case "RegisterKlantRekening":
                $Resultaat = $this->db->requestRegisterKlantRekening($Request["KlantklantID"], $Request["RekeningRekeningnummer"], $Request["RolRolnaam"]);
                break;
            default: 
                header('HTTP/1.0 403 Forbidden');
                die();
                break;
        }
        if(!$Silent){
            header('Content-Type: application/json');
            echo json_encode($Resultaat);
        }else{
            return $Resultaat;
        }
    }

}

?>