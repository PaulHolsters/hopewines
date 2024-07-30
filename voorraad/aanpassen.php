<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aanpassen product</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    <script src="../shared/overall.js"></script>
</head>
<body>
<?php
include ('../shared/hoofdmenu_2.php');
?>
<hr>
<h1>Aanpassen product</h1>
<?php
//terug naar overzicht
echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form><br>";
//aanpassen product

$ID="";
$appellatie = "";
$domein = "";
$volume = "";
$soort = "";

$naam = "";
$prijs = "";
$jaar = "";
$korting = "";
$aantal = "";


include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');

if(isset($_POST['modify'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    //pas het geselecteerde product aan
    $appellatieID = trim($_POST['appellatie']);
    $domeinID = trim($_POST['domein']);
    $volumeID = trim($_POST['volume']);
    $soortID = trim($_POST['soort']);
    $ID = trim($_POST['ID']);

    $naam = trim($_POST['naam']);
    $naam = str_replace('"','\"',$naam);
    $naam = str_replace("'","\'",$naam);

    $jaar = trim($_POST['jaar']);
    if(empty($jaar)){
        $jaar = "null";
    }

    $korting = trim($_POST['korting']);
    $prijs = trim($_POST['prijs']);

    //bepalen van de voorraad
    $aantal = $_POST['aantal'];
    if(isset($_POST['stock'])){
        if(!empty(trim($_POST['stock']))){
            $aantal = intval(trim($_POST['stock']));
        }
    }
    else{
        //stel hier vast wat je moet doen met de reeds aanwezige voorraad
        if(!empty($_POST['actieVoorraad'])&&!empty($_POST['voorraad'])){
            if($_POST['actieVoorraad']==='plus'){
                $aantal += intval(trim($_POST['voorraad']));
            }
            else{
                //checks zijn al uitgevoerd door onsubmit javascript functie
                $aantal -= intval(trim($_POST['voorraad']));
            }
        }
    }

    $sql = "UPDATE voorraad SET AppellatieID = ".$appellatieID.", DomeinID = ".$domeinID.", VolumeID = ".$volumeID.", SoortID = ".$soortID.", 
                                Naam = '".$naam."', Jaar = ".$jaar.", Korting = ".$korting.", Aantal = ".$aantal.",
                             Prijs = ".$prijs."
                             WHERE ID = ".$ID.";";
    $conn->query($sql);

    echo "<b>aanpassingen uitgevoerd</b><br>";

}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID = trim($_POST['ID']);
}

//haal alle info van het geselecteerde product op
$sql = 'SELECT a.Naam as Appellatie, v.Naam as Naam, Jaar, d.Naam as Domein, vol.Naam as Volume, s.Naam as Soort, Prijs, Aantal,Korting
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
            $prijs = $row['Prijs'];
            $jaar = $row['Jaar'];
            $korting = $row['Korting'];
            $aantal = $row['Aantal'];

        }
}

//presenteer het geselecteerde product
echo "<form action='".$_SERVER['PHP_SELF']."' method='post' onsubmit='return checkFormulier();'>";?>
     <label>Appellatie:
                <select name='appellatie'><option class="placeholder">--Selecteer een appellatie--</option>
<?php
$sql = 'SELECT ID, Naam FROM appellatie;';
$result = $conn->query($sql);
if(isset($result)){
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($appellatie === $row['Naam']){
                echo "<option class='appellatie'  value='".$row['ID']."' selected >".$row['Naam']."</option>";
            }
            else{
                echo "<option class='appellatie'  value='".$row['ID']."'>".$row['Naam']."</option>";
            }
        }
    }
}

echo "</select></label>";?>
<label>Filter: <input type='text' id='appellatieFilter'></label>
<button type="button"  onclick='filter("appellatie");'>Ok</button><button type='button' onclick='stopFilter("appellatie");'>Stop filter</button>
<br>
<?php
echo "
    <label>Naam (optioneel):
    <input type='text' name='naam' value='".$naam."'></label><br>
    
    <label>Jaar (optioneel):
    <input type='number' min='1500' max='3000' name='jaar' value='".$jaar."'></label><br>
    
    <label>Domein:
    <select name='domein'><option class='placeholder'>--Selecteer een domein--</option>";


$sql = 'SELECT ID, Naam FROM domein;';
$result = $conn->query($sql);
if(isset($result)){
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($domein === $row['Naam']){
                echo "<option class='domein'  value='".$row['ID']."' selected>".$row['Naam']."</option>";
            }
            else{
                echo "<option class='domein' value='".$row['ID']."'>".$row['Naam']."</option>";
            }
        }
    }
}

echo "</select></label>";?>
        <label>Filter: <input type='text' id='domeinFilter'></label><button type="button" onclick='filter("domein");'>Ok</button>
                    <button type='button' onclick='stopFilter("domein");'>Stop filter</button><br>
        <?php
        echo"
    <label>Korting: 
    <input type='number' min='0' value='".$korting."' max='100' name='korting' required>%</label><br>
    
    <label>Prijs inclusief BTW: &euro; 
    <input type='number' min='0.01' step='0.01' value='".$prijs."'  name='prijs' required></label><br>";

    //aanpassen voorraad
    //check bij onsubmit of de aantallen aanvaardbaar zijn zodat de gebruiker ze voor verzending nog kan corrigeren
    if($aantal===null){
        echo "<label>Aantal: <input type='number' min='0' name='stock'> stuks</label><br>";
    }
    else {
        echo "<label>Aantal stuks aanwezig: " . $aantal . "</label><br>";
        echo "
            <label>Selecteer je actie: Voorraad 
            <input type='radio' name='actieVoorraad' value='plus'>vermeerderen
            <input type='radio' name='actieVoorraad' value='min'>verminderen </label>
            <label>met: 
            <input type='number' min='1' name='voorraad'> stuks</label><button type='button' onclick='resetActie(\"voorraad\");'>reset</button><br>";
    }


echo "<label>Selecteer volume: </label>
    <select name='volume'>";

$sql = 'SELECT ID, Naam FROM volume ORDER BY ID;';
$result = $conn->query($sql);
if(isset($result)){
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($volume === $row['Naam']){
                echo "<option  value='".$row['ID']."' selected>".$row['Naam']."</option>";
            }
            else{
                echo "<option  value='".$row['ID']."'>".$row['Naam']."</option>";
            }
        }
    }
}
echo "</select><br>";

echo "<label>Selecteer soort: </label>
    <select name='soort'>";

$sql = 'SELECT ID, Naam FROM soort;';
$result = $conn->query($sql);
if(isset($result)){
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($soort === $row['Naam']){
                echo "<option  value='".$row['ID']."' selected>".$row['Naam']."</option>";
            }
            else{
                echo "<option  value='".$row['ID']."'>".$row['Naam']."</option>";
            }
        }
    }
}

echo "</select><br> 
<input type='hidden' value='".$ID."' name='ID'>
<input type='hidden' value='".$aantal."' name='aantal'>
<input type='submit' value='Invoeren' name='modify'></form>";
$conn->close();
?>
</body>
</html>
