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

        if(strpos(strtolower($Query), "insert") !== false){ return "msg:insert:succes"; }
        else if(strpos(strtolower($Query), "delete") !== false) { return "msg:delete:succes"; }
        else if(strpos(strtolower($Query), "update") !== false) { return "msg:update:succes"; }
        else{ return $stmt->fetchAll(); }
    }
}
?>