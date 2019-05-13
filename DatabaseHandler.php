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
        $isSuccesful = false;

        //Try to catch extra PDO error codes to handle errors with more clearity.
        try{
            $isSuccesful = $stmt->execute();
        }catch(PDOException $e){
            if($e->getCode() == 1062){
                return "msg:insert:failed:violation-or-duplicate-key";
            }else{
                throw $e;
            }
        }

        //Standard istrue check on the execute statement to at least be sure of an ok code.
        if(strpos(strtolower($Query), "insert") !== false)
            if($isSuccesful)
                return "msg:insert:succes";
            else
                return "msg:insert:failed";

        //To check if a delete/update was succesful, rowCount is widely used to see if it worked or not. As execute always returns true even if no rows were affected
        else if(strpos(strtolower($Query), "delete") !== false) 
            if($stmt->rowCount() > 0)
                return "msg:delete:succes";
            else
                return "msg:delete:failed:no-rows-affected"; 
        else if(strpos(strtolower($Query), "update") !== false)
            if($stmt->rowCount() > 0) 
                return "msg:update:succes";
            else
                return "msg:update:failed:no-rows-affected"; 
        else{ 
            $Result = $stmt->fetchAll(); 
            if($stmt->rowCount() !== 0)
                return $Result;
            else
                return "msg:select:empty";
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