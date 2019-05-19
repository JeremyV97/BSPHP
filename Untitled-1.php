<?php
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