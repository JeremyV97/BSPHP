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

    function requestRegisterKlant($klantID, $voornaam, $achternaam, $telefoon, $email, $adres, $gebruikersnaam, $wachtwoord, $bedrijfsnaam, $isGoedgekeurd){
        //return password_hash($Cypher, PASSWORD_DEFAULT);
        $Query = "Insert Into Klant Values (:klantID, :voornaam, :achternaam, :telefoon, :email, :adres, :gebruikersnaam, :wachtwoord, :bedrijfsnaam, :isGoedgekeurd);";
        $stmt = $this->pdo->prepare($Query);

        $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $stmt->bindParam(':klantID', $klantID);
        $stmt->bindParam(':voornaam', $voornaam);
        $stmt->bindParam(':achternaam', $achternaam);
        $stmt->bindParam(':telefoon', $telefoon);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':adres', $adres);
        $stmt->bindParam(':gebruikersnaam', $gebruikersnaam);
        $stmt->bindParam(':wachtwoord', $wachtwoord);
        $stmt->bindParam(':bedrijfsnaam', $bedrijfsnaam);
        $stmt->bindParam(':isGoedgekeurd', $isGoedgekeurd);

        if($stmt->execute()){
            return "msg:insert:succes:register_klant";
        }else{
            return "msg:insert:failed:register_klant";
        }
    }

    function requestRegisterBeheer($gebruikersnaam, $wachtwoord){
        $Query = "Insert Into Beheer Values (:gebruikersnaam, :wachtwoord);";
        $stmt = $this->pdo->prepare($Query);

        $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $stmt->bindParam(':gebruikersnaam', $gebruikersnaam);
        $stmt->bindParam(':wachtwoord', $wachtwoord);

        if($stmt->execute()){
            return "msg:insert:succes:register_beheer";
        }else{
            return "msg:insert:failed:register_beheer";
        }
    }

    function requestSecureLogin($Gebruiker, $Wachtwoord, $isGebruiker){
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
            return "msg:securelogin:failed:gebruikersnaam";
        }
        $gottenWachtwoord = $Result[0]["Wachtwoord"];
        if(password_verify($Wachtwoord, $gottenWachtwoord)){
            return "msg:securelogin:succes";
        }else{
            return "msg:securelogin:failed:wachtwoord";
        }
    }

}
?>