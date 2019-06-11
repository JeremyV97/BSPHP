<?php
//ini_set('display_errors',1); error_reporting(E_ALL);
?>
<html>
    <head>
        <title>Unit Test <?php echo date("H:i:s Y-m-d"); ?></title>
    </head>
<?php
/**
 * Class: UnitTest
 * @author Jeremy Vorrink
 * Description: Dit bestand draait bepaalde unit tests om de functies van dit systeem te testen
 */
class UnitTest{
    private $rh;
    private $succesfullUnitTests;
    private $totalUnitTests;

    /**
     * Function: Constructor
     * @author Jeremy Vorrink
     * Description: Draait de unit tests bij het aanroepen van de class
     */
    function __construct(){
        require("RequestHandler.php");
        $this->rh = new RequestHandler();
        $this->succesfullUnitTests = 0;
        $this->totalUnitTests = 0;

        $rsUnitSQL = $this->UnitTestSQL();

        echo "Running Unit Tests <br><br>";

        echo $rsUnitSQL . "<br>";

        echo "<br>";
        echo "Total Unit Tests: " . $this->totalUnitTests . "<br>";
        echo "Total Unit Tests Passed: " . $this->succesfullUnitTests . "<br>";
    }

    /**
     * Function: UnitTestSQL
     * @author Jeremy Vorrink
     * Description: Voert de unit test voor SQL functie uit
     */
    public function UnitTestSQL(){
        $this->totalUnitTests += 1;
        $SQL = "Select * From Beheer Where Gebruikersnaam = 'admin'";

        $Request = [
            "Request" => "SQL",
            "SQL" => $SQL,
        ];

        $Result = $this->rh->handleRequest($Request, true);

        if(empty($Result)){
            return "Unit Test (SQL) Failed: Result Empty";
        }else{
            $this->succesfullUnitTests += 1;
            return "Succes! Unit Test (SQL) Succeeded: Got a proper result";
        }
    }
}
?>
    <body>
        <?php
        $UnitTest = new UnitTest();
        ?>
    </body>
</html>
