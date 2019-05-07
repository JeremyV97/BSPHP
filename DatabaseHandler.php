<?php
class DatabaseHandler{

    private $pdo;
    private $LastId;
    
    private $host = "rdbms.strato.de";
    private $database = "DB3753754";
    private $user = "U3753754";
    private $pass = "4hYwFMsfWmfD";

    //Dit is de constructor, alle code dat hierin word geplaatst, word uitgevoerd bij het initializeren van deze class
    function __construct(){
        $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->database;",$this->user,$this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function request($Query){
        $stmt = $this->pdo->prepare($Query);
        $stmt->execute();

        //Todo check if insert/delete/update actually IS succesfull
        if(strpos(strtolower($Query), "insert") !== false){ return "msg:insert:succes"; }
        else if(strpos(strtolower($Query), "delete") !== false) { return "msg:delete:succes"; }
        else if(strpos(strtolower($Query), "update") !== false) { return "msg:update:succes"; }
        else{ 
            $Result = $stmt->fetchAll(); 
            if($stmt->rowCount() !== 0){
                return $Result;
            }else{
                return "msg:select:empty";
            }
        }
    }

    function requestLogin($Gebruiker, $Wachtwoord, $isGebruiker){
        $Query = "";
        if($isGebruiker == true){
            $Query = "select Wachtwoord from Klant where Gebruikersnaam = '" . $Gebruiker . "';";
        }
        if($isGebruiker == false){
            $Query = "select Wachtwoord from Beheer where Gebruikersnaam = '" . $Gebruiker . "';";
        }

        $stmt = $this->pdo->prepare($Query);
        $stmt->execute();

        $Result = $stmt->fetchAll();
        if($stmt->rowCount() !== 1){
            return "msg:login:failed:gebruikersnaam";
        }
        $gottenWachtwoord = $Result[0]["Wachtwoord"];
        if($gottenWachtwoord == $Wachtwoord){
            return "msg:login:succes";
        }else{
            return "msg:login:failed:wachtwoord";
        }
    }
}
?>