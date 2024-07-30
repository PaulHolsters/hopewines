<?php
$ID = "";
$bestelID = "";
$productID = "";
$aantal = "";
$korting = "";
$aanpassen = false;
include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
//product aanmaken op basis van wat de gebruiker heeft meegegeven hieronder
if(isset($_POST['add'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    //nieuw item toevoegen
    $ID = $_POST['ID'];
    $productID = $_POST['product'];
    $aantal = trim($_POST['aantal']);
    $korting = trim($_POST['korting']);
    if(empty($aantal)){
        $aantal="NULL";
    }
    if(empty($korting))$korting = 0;
    //invoeren van nieuw product: mogelijkheid tot manuele korting inbouwen
    $sql = "INSERT INTO item VALUES(NULL,".$productID.", ".$ID.",".$aantal.",".$korting.",NULL,NULL,NULL,NULL);";
    $result = $conn->query($sql);
    if($aantal!=='NULL' && $result ){
        $sql = "UPDATE voorraad SET Aantal = Aantal - ".$aantal." WHERE ID = ".$productID.";";
        $conn->query($sql);
    }
}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    //nieuw item =>productID niet bekend
    $ID = $_POST['ID'];
}
else if(isset($_POST['sentAanpassen'])&&$_SERVER['REQUEST_METHOD'] === 'POST'){
    //aanpassen bestaand item (vorige pagina)
    //presenteren van gegevens
    $bestelID = $_POST['bestelID'];
    $productID = $_POST['productID'];
    $korting = $_POST['manKorting'];// 0 of een ander getal (max 100)
    $aantal = $_POST['aantal'];//kan empty zijn!!!
    $ID = $bestelID;
    $aanpassen = true;
}
else if(isset($_POST['aanpassen'])&&$_SERVER['REQUEST_METHOD'] === 'POST'){
    //aanpassen gegevens en representeren
    $bestelID = $_POST['ID'];
    $nieuwProductID = $_POST['product'];//kan nooit leeg zijn!
    $nieuwAantal = $_POST['aantal'];//kan empty zijn!!!
    $aantal = $nieuwAantal;
    $oudAantal = $_POST['oudAantal'];//kan empty zijn!!!
    $oudProductID = $_POST['oudProduct'];//kan nooit leeg zijn!
    $productID = $nieuwProductID;
    $ID = $bestelID;
    $korting = trim($_POST['korting']);
    if(empty($korting))$korting = 0;
    $aanpassen = true;
    //algemeen geldt:

    //AANTAL:
    //aantal = leeg
    //aantal = 1 of meer (je kan niet voor 0 items kiezen)
    //je kan niet meer producten kiezen dan er beschikbaar zijn
    //de beschikbaarheid wordt getest door de javascript functie checkItem();

    //PRODUCTKORTING:
    //deze is 0 of maximaal 100 (uitgedrukt in %)
    //indien leeg wordt deze automatisch omgezet naar 0

    if($oudProductID === $nieuwProductID) {
        //de gebruiker is niet van product veranderd =>enkel aantal en korting moeten worden beschouwd
        //korting moet gewoon worden overschreven
        //voor het aantal moet ook de voorraad worden aangepast
        if(empty($oudAantal)){
            if(empty($nieuwAantal)){
                //er verandert niets behalve eventueel de korting
                $sql = "UPDATE item SET Korting = ".$korting." WHERE ProductID = ".$nieuwProductID." AND BestelID = ".$bestelID.";";
                $result = $conn->query($sql);
            }
            else{
                //aantal en korting aanpassen
                $sql = "UPDATE item SET Aantal = " . $nieuwAantal . ", Korting = ".$korting." WHERE ProductID = ".$nieuwProductID." 
                AND BestelID = ".$bestelID.";";
                $result = $conn->query($sql);
                if ($result) {
                    $sql = "UPDATE voorraad SET Aantal = Aantal - " . $nieuwAantal . " WHERE ID = ".$nieuwProductID.";";
                    $result = $conn->query($sql);
                }
            }
        }
        else{
            //gebruiker blijft bij hetzelfde product
            //er zat reeds een aantal in de databank
            if(empty($nieuwAantal)){
                //de voorraad moet aangepast worden
                //aantal = null
                $sql = "UPDATE item SET Aantal = NULL, Korting = ".$korting." WHERE ProductID = ".$nieuwProductID." 
                AND BestelID = ".$bestelID.";";
                $result = $conn->query($sql);
                if ($result) {
                    $sql = "UPDATE voorraad SET Aantal = Aantal + " . $oudAantal . " WHERE ID = ".$nieuwProductID.";";
                    $result = $conn->query($sql);
                }
            }
            else{
                //er is ook een nieuw aantal aangevraagd
                $updateAantal = $nieuwAantal - $oudAantal;
                $sql = "UPDATE item SET Aantal = Aantal + ".$updateAantal.", Korting = ".$korting." WHERE ProductID = ".$nieuwProductID." 
                AND BestelID = ".$bestelID.";";
                $result = $conn->query($sql);
                if ($result) {
                    $sql = "UPDATE voorraad SET Aantal = Aantal - " . $updateAantal . " WHERE ID = ".$nieuwProductID.";";
                    $result = $conn->query($sql);
                }
            }
        }
    }
    else{
        //de gebruiker kiest voor een nieuw product
        //de gebruiker kan niet kiezen voor een ander product dat reeds op de bestelling staat
        //maar wel voor het product dat hij al wilde aanpasssen natuurlijk

        //STAP1: pas de voorraad van het oude product aan indien oud aantal is niet NULL
        //STAP2: voer het nieuwe product in
        //STAP 3: pas voorraad aan

        //STAP 1
        if(!empty($oudAantal)){
            $sql = "UPDATE voorraad SET Aantal = Aantal + " . $oudAantal . " WHERE ID = ".$oudProductID.";";
            $result = $conn->query($sql);
        }
        //STAP 2
        if(!empty($nieuwAantal)){
            $sql = "UPDATE item SET Aantal = ".$nieuwAantal.", Korting = ".$korting.", ProductID = ".$nieuwProductID." 
            WHERE BestelID = ".$bestelID." AND ProductID = ".$oudProductID.";";
        }
        else{
            $sql = "UPDATE item SET Aantal = NULL , Korting = ".$korting.", ProductID = ".$nieuwProductID." 
            WHERE BestelID = ".$bestelID." AND ProductID = ".$oudProductID.";";
        }
        //STAP 3
        $result = $conn->query($sql);
        if($result && !empty($nieuwAantal)){
            $sql = "UPDATE voorraad SET Aantal = Aantal - " . $nieuwAantal . " WHERE ID = ".$nieuwProductID.";";
            $result = $conn->query($sql);
        }
    }
}
?>

<!doctype html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <?php
        if($aanpassen){
            echo "<title>Aanpassen bestelitem</title>";
        }
        else{
            echo "<title>Nieuw bestelitem</title>";
        }
        ?>
        <link href="../../shared/overall.css" rel="stylesheet" type="text/css">
        <script src="../../shared/overall.js"></script>
    </head>
    <body>
    <?php
    include('../../shared/hoofdmenu_3.php');
    ?>
    <hr>
    <?php
    if($aanpassen){
        echo "<h1>Aanpassen item</h1>";
    }
    else{
        echo "<h1>Nieuw bestelitem</h1>";
    }
    ?>

    <?php
    //terug naar overzicht
    //checkItem stelt wat het maximaal toegelaten aantal is
    echo "<form action='../bestelling.php' method='post'><input type='hidden' value='".$ID."' name='ID'>
            <input type='submit' value='Ga terug' name='aanpassen'></form><br>
            <form action='" .$_SERVER['PHP_SELF']."' method='post' onsubmit='return checkItem();'>";?>

    <?php
    //selectie van het product
    ?>
    <label>Product:
        <select name='product'><option class="placeholder">--Selecteer een een product--</option>
    <?php
    //zorg dat er enkel wijnen op staan die nog niet besteld zijn
    if(!empty($productID)){
        $sql = 'SELECT v.ID as ID  , CONCAT_WS(" ",a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, Prijs, Aantal
            FROM voorraad v 
            JOIN appellatie a ON v.AppellatieID = a.ID
            JOIN domein d ON v.DomeinID = d.ID
            JOIN volume vol ON v.VolumeID = vol.ID
            JOIN soort s ON v.SoortID = s.ID
            WHERE v.ID NOT IN (SELECT ProductID FROM item WHERE BestelID = '.$ID.')
            OR v.ID = '.$productID.';';
    }
    else{
        $sql = 'SELECT v.ID as ID  , CONCAT_WS(" ",a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, Prijs, Aantal
            FROM voorraad v 
            JOIN appellatie a ON v.AppellatieID = a.ID
            JOIN domein d ON v.DomeinID = d.ID
            JOIN volume vol ON v.VolumeID = vol.ID
            JOIN soort s ON v.SoortID = s.ID
            WHERE v.ID NOT IN (SELECT ProductID FROM item WHERE BestelID = '.$ID.');';
    }
    $result = $conn->query($sql);
    if(isset($result)){
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($aanpassen){
                    if($row["ID"]===$productID){
                        if(!empty($aantal)){
                            echo "<option class='product' value='".$row["ID"]."' selected>".$row["Product"]."    &euro; ".$row["Prijs"]." incl.BTW   -- ".($row["Aantal"]+$aantal)." stuks beschikbaar</option>";
                        }
                        else{
                            echo "<option class='product' value='".$row["ID"]."' selected>".$row["Product"]."    &euro; ".$row["Prijs"]." incl.BTW   -- ".$row["Aantal"]." stuks beschikbaar</option>";
                        }
                    }
                    else{
                        echo "<option class='product' value='".$row["ID"]."'>".$row["Product"]."    &euro; ".$row["Prijs"]." incl.BTW   -- ".$row["Aantal"]." stuks beschikbaar</option>";
                    }
                }
                else{
                    echo "<option class='product' value='".$row["ID"]."'>".$row["Product"]."    &euro; ".$row["Prijs"]." incl.BTW   -- ".$row["Aantal"]." stuks beschikbaar</option>";
                }
            }
        }
    }
    $conn->close();
    echo "</select></label><label>Filter: <input type='text' id='productFilter'></label>
            <button type='button' onclick='filter(\"product\");'>Ok</button>
            <button type='button' onclick='stopFilter(\"product\");'>Stop filter</button><br>";
    //einde selectie product
            //bepalen aantal
    if($aanpassen){
        if(empty($aantal)){
            echo"<label>Aantal (nog niet ingegeven): <input type='number' name='aantal' min='1'>  stuks</label><br>";
        }
        else{
            echo"<label>Aantal: <input type='number' name='aantal' min='1' value='".$aantal."'>  stuks</label><br>";
        }
        echo"<label>Manuele korting: <input type='number' name='korting' min='0' max='100' value='".$korting."'>%</label><br>";
        echo "<input type='hidden' value='".$ID."' name='ID'>
                <input type='hidden' value='".$aantal."' name='oudAantal'>
                <input type='hidden' value='".$productID."' name='oudProduct'>
                <input type='hidden' value='true' name='modus'>
                <input type='submit' value='Aanpassen' name='aanpassen'></form>";
    }
    else{
        echo"<label>Aantal: <input type='number' name='aantal' min='1'>  stuks</label><br>";
        echo"<label>Manuele korting: <input type='number' name='korting' min='0' max='100' value='0'>%</label><br>";
        echo "<input type='hidden' value='".$ID."' name='ID'><input type='hidden' value='false' name='modus'><input type='submit' value='Invoeren' name='add'></form>";
    }
    ?>
    </body>
</html>
