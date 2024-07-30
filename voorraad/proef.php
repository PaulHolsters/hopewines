<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aanpassen proefflessen</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    <script src="../shared/overall.js"></script>
</head>
<body>
<?php
include ('../shared/hoofdmenu_2.php');
?>
<hr>
<h1>Aanpassen proefflessen</h1>
<?php
//terug naar overzicht
echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form><br>";
//aanpassen proefflessen

$ID="";
$appellatie = "";
$domein = "";
$volume = "";
$soort = "";
$naam = "";
$jaar = "";
$aantal = "";


include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');

if(isset($_POST['modify'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    //pas het geselecteerde product aan
    $ID = trim($_POST['ID']);

    //bepalen van de voorraad
    $aantal = $_POST['aantal'];
    $proef = $_POST['aantalProef'];
    //stel hier vast wat je moet doen met de reeds aanwezige voorraad
    if(!empty($_POST['actieProef'])&&!empty($_POST['proef'])){
        if($_POST['actieProef']==='plus'){
            $aantal -= intval(trim($_POST['proef']));
            $proef += intval(trim($_POST['proef']));
        }
        else{
            //checks zijn al uitgevoerd door onsubmit javascript functie
            $aantal += intval(trim($_POST['proef']));
            $proef -= intval(trim($_POST['proef']));
        }
    }


    $sql = "UPDATE voorraad SET Aantal = ".$aantal.", Proef = ".$proef." WHERE ID = ".$ID.";";
    $conn->query($sql);

    echo "<b>aanpassingen uitgevoerd</b><br>";

}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID = trim($_POST['ID']);
}

//haal alle info van het geselecteerde product op
$sql = 'SELECT a.Naam as Appellatie, v.Naam as Naam, Jaar, d.Naam as Domein, vol.Naam as Volume, s.Naam as Soort, Aantal,Proef
                FROM voorraad v JOIN appellatie a ON v.AppellatieID = a.ID
                JOIN domein d ON v.DomeinID = d.ID
                JOIN volume vol ON v.VolumeID = vol.ID
                JOIN soort s ON v.SoortID = s.ID
                WHERE v.ID = '.$ID.';';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
            $appellatie = $row['Appellatie'];
            $domein = $row['Domein'];
            $volume = $row['Volume'];
            $soort = $row['Soort'];
            $naam = $row['Naam'];
            $jaar = $row['Jaar'];
            $aantal = $row['Aantal'];
            $proef = $row['Proef'];
            if($aantal===null){
                header('Location: index.php');
            }
        }
}

//presenteer het geselecteerde product
echo "<form action='".$_SERVER['PHP_SELF']."' method='post' onsubmit='return checkProef();'>";?>
   <?php
   echo "<label>Gekozen product: ".$appellatie." ".$domein." ".$naam." ".$jaar." ".$volume." ".$soort."</label><br>";
   echo "<label>Aantal flessen in voorraad: ".$aantal." stuks</label><br>";
   echo "<label>Aantal proefflessen aanwezig: " . $proef . " stuks</label><br>";
   echo "
            <label>Selecteer je actie: Aantal proefflessen
            <input type='radio' name='actieProef' value='plus'>vermeerderen
            <input type='radio' name='actieProef' value='min'>verminderen </label>
            <label>met: 
            <input type='number' min='1' name='proef'> stuks</label><button type='button' onclick='resetActie(\"proef\");'>reset</button><br>";

echo"
<input type='hidden' value='".$ID."' name='ID'>
<input type='hidden' value='".$aantal."' name='aantal'>
<input type='hidden' value='".$proef."' name='aantalProef'>
<input type='submit' value='Invoeren' name='modify'></form>";
$conn->close();
?>
</body>
</html>
