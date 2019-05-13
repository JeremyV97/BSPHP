<?php
<<<<<<< HEAD
If(isset($_POST[“btnSubmit”])){
	echo $_POST{“txtForm};
}
?>

<html>
<body>
	<form method=“POST”>
	<input type=“text” name=“factuurnummer” placeholder=“factuurnummer”/>
	<input type=“text” name=“Voornaam” placeholder=“Voornaam”/>
	<input type=“text” name=“Achternaam” placeholder=“Achternaam”/>	
    <input type=“text” name=“Adres” placeholder=“Adres”/>	
	<input type=“text” name=“Product” placeholder=“Product”/>	
    <input type=“text” name=“Email” placeholder=“Email”/>	
	<input type=“text” name=“Telefoon” placeholder=“Telefoon”/>
    <input type="submit" method="POST" name="btnSubmit" value="Opslaan"/>
    </form>
</body>
</html>

<?php
class factuur{
    private $factuurnummer;
    private $Voornaam;
    private $Achternaam;
    private $adres;
    private $Product;
    private $email;
    private $Telefoon;

function __construct($factuurnummer, $Voornaam , $Achternaam, $adres, $Product, $email,$Telefoon){
    $this ->Factuurnummer = $factuurnummer;
    $this ->$Voornaam = $Voornaam;
    
}

    
}
=======

ini_set('display_errors',1); error_reporting(E_ALL);


require("RequestHandler.php");
if(isset($_POST)){
    $json = file_get_contents('php://input');
    $request = json_decode($json, true);

    $rh = new RequestHandler();
    if(!isset($request["Request"])){
        header('HTTP/1.0 403 Forbidden');
    }
    if($request["Request"] == "SQL"){
        $rh->handleRequest($request["SQL"]);
    }
    if($request["Request"] == "Login"){
        $rh->handleLogin($request["Gebruikersnaam"], $request["Wachtwoord"], $request["isGebruiker"]);
    }
    
}else{
    header('HTTP/1.0 403 Forbidden');
}



?>
>>>>>>> 3cdaa5a1f8b3ab749b11fa44872194691a98da95
