<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aanpassen onverkoopbare flessen</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    <script src="../shared/overall.js"></script>
</head>
<body>
<?php
include ('../shared/hoofdmenu_2.php');
?>
<hr>
<h1>Aanpassen onverkoopbare flessen</h1>
<?php
//terug naar overzicht
echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form><br>";
//aanpassen onverkoopbare flessen

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
    $onverkoopbaar = $_POST['aantalOnverkoopbaar'];
    //stel hier vast wat je moet doen met de reeds aanwezige voorraad
    if(!empty($_POST['actieOnverkoopbaar'])&&!empty($_POST['onverkoopbaar'])){
        if($_POST['actieOnverkoopbaar']==='plus'){
            $aantal -= intval(trim($_POST['onverkoopbaar']));
            $onverkoopbaar += intval(trim($_POST['onverkoopbaar']));
        }
        else{
            //checks zijn al uitgevoerd door onsubmit javascript functie
            $aantal += intval(trim($_POST['onverkoopbaar']));
            $onverkoopbaar -= intval(trim($_POST['onverkoopbaar']));
        }
    }


    $sql = "UPDATE voorraad SET Aantal = ".$aantal.", Onverkoopbaar = ".$onverkoopbaar." WHERE ID = ".$ID.";";
    $conn->query($sql);

    echo "<b>aanpassingen uitgevoerd</b><br>";

}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID = trim($_POST['ID']);
}

//haal alle info van het geselecteerde product op
$sql = 'SELECT a.Naam as Appellatie, v.Naam as Naam, Jaar, d.Naam as Domein, vol.Naam as Volume, s.Naam as Soort, Aantal,Onverkoopbaar
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
            $onverkoopbaar = $row['Onverkoopbaar'];
            if($aantal===null){
                header('Location: index.php');
            }
        }
}

//presenteer het geselecteerde product
echo "<form action='".$_SERVER['PHP_SELF']."' method='post' onsubmit='return checkOnverkoopbaar();'>";?>
   <?php
   echo "<label>Gekozen product: ".$appellatie." ".$domein." ".$naam." ".$jaar." ".$volume." ".$soort."</label><br>";
   echo "<label>Aantal flessen in voorraad: ".$aantal." stuks</label><br>";
   echo "<label>Aantal onverkoopbare flessen aanwezig: " . $onverkoopbaar . " stuks</label><br>";
   echo "
            <label>Selecteer je actie: Aantal onverkoopbare flessen
            <input type='radio' name='actieOnverkoopbaar' value='plus'>vermeerderen
            <input type='radio' name='actieOnverkoopbaar' value='min'>verminderen </label>
            <label>met: 
            <input type='number' min='1' name='onverkoopbaar'> stuks</label><button type='button' onclick='resetActie(\"onverkoopbaar\");'>reset</button><br>";

echo"
<input type='hidden' value='".$ID."' name='ID'>
<input type='hidden' value='".$aantal."' name='aantal'>
<input type='hidden' value='".$onverkoopbaar."' name='aantalOnverkoopbaar'>
<input type='submit' value='Invoeren' name='modify'></form>";
$conn->close();
?>
</body>
</html>
