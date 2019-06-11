<?php
/**
 * Class: DatabaseHandler
 * @author Jeremy Vorrink
 * Description: Database class dat praat met de database, en geeft de resultaat terug
 */
class DatabaseHandler{

    private $pdo;
    private $LastId;
    
    private $host = "rdbms.strato.de";
    private $database = "DB3753754";
    private $user = "U3753754";
    private $pass = "4hYwFMsfWmfD";

    /**
     * Function: Constructor
     * @author Jeremy Vorrink
     * Description: Dit is de constructor, alle code dat hierin word geplaatst, word uitgevoerd bij het initializeren van deze class
     */
    function __construct(){
        $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->database;",$this->user,$this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Function: Request
     * @param $Query: SQL Query die uitgevoerd word
     * @author Jeremy Vorrink
     * Description: Voert de opgegeven SQL statement direct uit op de database
     */
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

    /**
     * Function: requestLogin
     * @param $Gebruiker
     * @param $Wachtwoord
     * @param $isGebruiker
     * @author Jeremy Vorrink
     * Description: Met de opgegeven gegevens checkt de functie of de gebruiker bestaat, en de opgegeven wachtwoord overeenkomt met de hash
     */
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

    /**
     * Function: requestRegisterKlant
     * @param $klantID
     * @param $voornaam
     * @param $achternaam
     * @param $telefoon
     * @param $email
     * @param $adres
     * @param $gebruikersnaam
     * @param $wachtwoord
     * @param $bedrijfsnaam
     * @param $isGoedgekeurd
     * @author Jeremy Vorrink
     * Description: Slaat de opgegeven gegevens op in de database tabel Klant
     */
    function requestRegisterKlant($klantID, $voornaam, $achternaam, $telefoon, $email, $adres, $gebruikersnaam, $wachtwoord, $bedrijfsnaam, $isGoedgekeurd){
        $Query = "Insert Into Klant Values (:klantID, :voornaam, :achternaam, :telefoon, :email, :adres, :gebruikersnaam, :wachtwoord, :bedrijfsnaam, :isGoedgekeurd);";
        $stmt = $this->pdo->prepare($Query);

        $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $stmt->bindparam(':klantID', $klantID);
        $stmt->bindparam(':voornaam', $voornaam);
        $stmt->bindparam(':achternaam', $achternaam);
        $stmt->bindparam(':telefoon', $telefoon);
        $stmt->bindparam(':email', $email);
        $stmt->bindparam(':adres', $adres);
        $stmt->bindparam(':gebruikersnaam', $gebruikersnaam);
        $stmt->bindparam(':wachtwoord', $wachtwoord);
        $stmt->bindparam(':bedrijfsnaam', $bedrijfsnaam);
        $stmt->bindparam(':isGoedgekeurd', $isGoedgekeurd);

        if($stmt->execute()){
            return "msg:insert:succes:register_klant";
        }else{
            return "msg:insert:failed:register_klant";
        }
    }

    /**
     * Function: requestRegisterBeheer
     * @param $gebruikeresnaam
     * @param $wachtwoord
     * @author Jeremy Vorrink
     * Description: Slaat de opgegeven gegevens op in de database tabel Beheer
     */
    function requestRegisterBeheer($gebruikersnaam, $wachtwoord){
        $Query = "Insert Into Beheer Values (:gebruikersnaam, :wachtwoord);";
        $stmt = $this->pdo->prepare($Query);

        $wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        $stmt->bindparam(':gebruikersnaam', $gebruikersnaam);
        $stmt->bindparam(':wachtwoord', $wachtwoord);

        if($stmt->execute()){
            return "msg:insert:succes:register_beheer";
        }else{
            return "msg:insert:failed:register_beheer";
        }
    }

    /**
     * Function: requestSecureLogin
     * @param $Gebruiker
     * @param $Wachtwoord
     * @param $isGebruiker
     * @author Jeremy Vorrink
     * Description: Checked de gegevens met de database en checked de wachtwoord met password_verify
     */
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

    /**
     * Function: requestPasswordUpdate
     * @param $Gebruikersnaam
     * @param $Wachtwoord
     * @param $isGebruiker
     * @author Jeremy Vorrink
     * Description: Voert een nieuwe wachtwoord in voor de opgegeven gebruiker
     */
    function requestPasswordUpdate($Gebruikersnaam, $Wachtwoord, $isGebruiker){
        $Tabel = "Beheer";
        if($isGebruiker)
            $Tabel = "Klant";

        $Query = "Update $Tabel set Wachtwoord = :Wachtwoord Where Gebruikersnaam = :Gebruikersnaam;";
        $stmt = $this->pdo->prepare($Query);

        $Wachtwoord = password_hash($Wachtwoord, PASSWORD_DEFAULT);

        $stmt->bindparam(":Wachtwoord", $Wachtwoord);
        $stmt->bindparam(":Gebruikersnaam", $Gebruikersnaam);

        $stmt->execute();
        if($stmt->rowCount() > 0) 
            return "msg:passwordupdate:succes";
        else
            return "msg:passwordupdate:failed:no-rows-affected"; 
    }

    /**
     * Function: requestRegisterRekening
     * @param $Rekeningnummer
     * @param $Rekeningsoort
     * @param $Rente
     * @param $Saldo
     * @author Jeremy Vorrink
     * Description: Slaat de opgegeven gegevens op in de database tabel Rekening
     */
    function requestRegisterRekening($Rekeningnummer, $Rekeningsoort, $Rente, $Saldo){
        $Query = "Insert into Rekening Values (:Rekeningnummer, :Rekeningsoort, :Rente, :Saldo);";
        $stmt = $this->pdo->prepare($Query);

        $stmt->bindparam(":Rekeningnummer", $Rekeningnummer);
        $stmt->bindparam(":Rekeningsoort", $Rekeningsoort);
        $stmt->bindparam(":Rente", $Rente);
        $stmt->bindparam(":Saldo", $Saldo);

        if($stmt->execute()){
            return "msg:insert:succes:register_rekening";
        }else{
            return "msg:insert:failed:register_rekening";
        }
    }

    /**
     * Function: requestRegisterKlantRekening
     * @param $KlantID
     * @param $Rekeningnummer
     * @param $Rolnaam
     * @author Jeremy Vorrink
     * Description: Voert de opgegeven gegevens op in de database tabel KlantRekening
     */
    function requestRegisterKlantRekening($KlantID, $Rekeningnummer, $Rolnaam){
        $Query = "Insert Into KlantRekening Values (:KlantID, :Rekeningnummer, :Rolnaam);";
        $stmt = $this->pdo->prepare($Query);

        $stmt->bindparam(":KlantID", $KlantID);
        $stmt->bindparam(":Rekeningnummer", $Rekeningnummer);
        $stmt->bindparam(":Rolnaam", $Rolnaam);

        if($stmt->execute()){
            return "msg:insert:succes:register_klantrekening";
        }else{
            return "msg:insert:failed:register_klantrekening";
        }
    }

    /*
        Inge
    */
    function requestRegisterTransaction($transactieID, $Datum, $Tijd, $Opmerkingen){
        $Query = "Insert Into Transactie Values(:TransactieID, :Datum, :Tijd, :Opmerking);";
        $stmt = $this->pdo->prepare($Query);

        $stmt->bindparam(":TransactieID", $transactieID);
        $stmt->bindparam(":Datum", $Datum);
        $stmt->bindparam(":Tijd", $Tijd);
        $stmt->bindparam(":Opmerking", $Opmerkingen);

        if($stmt->execute()){
            return "msg:insert:succes:register_transaction";
        }else{
            return "msg:insert:failed:register_transaction";
        }

    }
    /**
     * @author Mike 
     */
    function requestRegisterTransactionIn($Rekeningnummer, $TransactieID, $Bedrag){
        $Query = "Insert Into TransactieIn Values(:RekeningRekeningnummer, :TransactieTransactieID, :Bedrag);";
        $stmt = $this->pdo->prepare($Query);    

        $stmt->bindparam(":RekeningRekeningnummer", $Rekeningnummer);
        $stmt->bindparam(":TransactieTransactieID", $TransactieID);
        $stmt->bindparam(":Bedrag", $Bedrag);

        if($stmt->execute()){
            return "msg:insert:succes:register_transactiein";
        } else{
            return "msg:insert:failed:register_transactiein";
        }
    }

    function requestRegisterTransactieUit($Rekeningnummer, $TransactieID, $Bedrag){

    }


    //Paul
    function requestRegisterBankpas($KlantID, $Pasnummer, $Pincode){
        $Query = "Insert Into Bankpas Values(:KlantID, :Pasnummer, :Pincode);";
        $stmt = $this->pdo->prepare($Query);

        $stmt->bindparam(":KlantID", $KlantID);
        $stmt->bindparam(":Pasnummer", $Pasnummer);
        $stmt->bindparam(":Pincode", $Pincode);


        if($stmt->execute()){
            return "msg:insert:succes:register_Bankpas";
        }else{
            return "msg:insert:failed:register_Bankpas";
        }


    }

    function requestRegisterZwarteLijst($KlantID, $Reden, $Opmerking){ //Door Rutger
        $Query = "Insert Into ZwarteLijst Values(:KlantID, :Reden, :Opmerking);";
        $stmt = $this->pdo->prepare($Query);
        $stmt->bindparam(":KlantID", $KlantID);
        $stmt->bindparam(":Reden", $Reden);
        $stmt->bindparam(":Opmerking", $Opmerkingen);
        if($stmt->execute()){
            return "msg:insert:succes:register_zwartelijst";
        }else{
            return "msg:insert:failed:register_zwartelijst";
        }
    }

}
?>
