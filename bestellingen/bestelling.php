<?php
session_start();
$_SESSION['bestelID']="";
//check hier of het een nieuwe bestelling is dan wel één die zal worden aangepast of gefactureerd
$klant = "";
$datum = "";
$ID = "";
$kortingklant = 0;
$nieuw = false;
$factuur = false;
include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
if(isset($_POST['nieuw'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $nieuw = true;
    //nog geen klant naam
    $sql = 'INSERT INTO bestelling VALUES(NULL,NULL,DATE(NOW()),NULL,0,FALSE ,NULL,FALSE,1);';
    $result = $conn->query($sql);

    //get ID
    $sql = 'SELECT MAX(ID) as ID
            FROM bestelling;';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ID = $row['ID'];
        }
    }
}
else if(isset($_POST['aanpassen'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    //haal klantgegevens en toon ze hieronder
    $ID = $_POST['ID'];
    if(isset($_POST['korting'])){
        $kortingklant = $_POST['korting'];
        if(empty($kortingklant)){
            $kortingklant = 0;
        }
    }
    else{
        $sql = 'SELECT k.Korting AS Korting
            FROM klant k JOIN bestelling b ON b.KlantID = k.ID
            WHERE b.ID = '.$ID.';';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $kortingklant = $row['Korting'];
            }
        }
    }

    $sql = 'SELECT k.Naam AS Klant, b.Datum as Datum
            FROM bestelling b JOIN klant k ON b.KlantID = k.ID
            WHERE b.ID = '.$ID.';';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $klant = $row['Klant'];
            $datum = $row['Datum'];
        }
    }
    else{
        $sql = 'SELECT Datum
            FROM bestelling
            WHERE ID = '.$ID.';';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $datum = $row['Datum'];
            }
        }
    }
}
else if(isset($_POST['bewaarKlant'])&&$_SERVER['REQUEST_METHOD'] === 'POST'){
    $ID = $_POST['ID'];
    $klantID = $_POST['klant'];
    if(!empty($klantID)){
        $sql = "UPDATE bestelling SET KlantID = ".$klantID."
                             WHERE ID = ".$ID.";";
        $conn->query($sql);

        $sql = 'SELECT k.Naam AS Klant, k.Korting as Korting, b.Datum as Datum
            FROM bestelling b JOIN klant k ON b.KlantID = k.ID
            WHERE b.ID = '.$ID.';';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $klant = $row['Klant'];
                $datum = $row['Datum'];
                $kortingklant = $row['Korting'];
            }
            echo "<b>Klantgegevens bewaard.</b><br>";
        }
        else{
            echo "<b>Aanpassingen niet doorgevoerd.</b><br>";
        }
    }
    else{

        $sql = "UPDATE bestelling SET KlantID = null
                             WHERE ID = ".$ID.";";
        $conn->query($sql);

        $sql = 'SELECT Datum
            FROM bestelling 
            WHERE ID = '.$ID.';';
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $datum = $row['Datum'];
            }
            $klant = "";
            echo "<b>Klantgegevens bewaard.</b><br>";
        }
        else{
            echo "<b>Aanpassingen niet doorgevoerd.</b><br>";
        }
    }
}
else if(isset($_POST['del'])&&$_SERVER['REQUEST_METHOD'] === 'POST'){
    //item verwijderen van de bestelling
    $bestelID = $_POST['bestelID'];
    $productID = $_POST['productID'];
    $aantal = $_POST['aantal'];

    $sql = "DELETE FROM item WHERE ProductID = ".$productID." AND BestelID = ".$bestelID.";";
    $result = $conn->query($sql);

    if(!empty($aantal) && $result){
        $sql = 'UPDATE voorraad
            SET Aantal = Aantal + '.$aantal.'
            WHERE ID = '.$productID.';';
        $result = $conn->query($sql);
    }

    $ID = $bestelID;
    //klant gegevens opnieuw ophalen
    $sql = 'SELECT k.Korting AS Korting, k.Naam AS Klant
            FROM klant k JOIN bestelling b ON b.KlantID = k.ID
            WHERE b.ID = '.$ID.';';
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kortingklant = $row['Korting'];
            $klant = $row['Klant'];
        }
    }
}
else if(isset($_POST['factuur'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "inside factuur";exit;
    $ID = $_POST['ID'];//BestelID
    $factuurdatum = $_POST['factuurdatum'];
    $_SESSION['bestelID']= $ID;
    $factuur =true;
    $factuurnr = 190000;
    //insert factuurnummer
    $sql = 'SELECT MAX(Factuurnummer) as Factuurnummer
            FROM bestelling WHERE Factuurnummer IS NOT NULL;';
    //echo $sql;exit;
    $result = $conn->query($sql);
    if($result){
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sql = 'UPDATE bestelling
                SET Factuurnummer = '.$row['Factuurnummer'].' + 1,
                Factuurdatum = "'.$factuurdatum.'"
                WHERE ID = '.$ID.';';
                $result1 = $conn->query($sql);
            }
        }
    }
    else{
        $sql = 'UPDATE bestelling
                SET Factuurnummer = '.$factuurnr.' + 1,
                Factuurdatum = "'.$factuurdatum.'"
                WHERE ID = '.$ID.';';
        $result1 = $conn->query($sql);
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
        if($nieuw){
            echo "<title>Nieuwe bestelling</title>";
        }
        else{
            echo "<title>Lopende bestelling</title>";
        }
        ?>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
        <script src="../shared/overall.js"></script>
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <?php
    if($nieuw){
        echo "<h1>Nieuwe bestelling</h1>";
    }
    else{
        echo "<h1>Lopende bestelling</h1>";
    }
    if(!$factuur){
        //bestelling kan zonder klantnaam aangemaakt worden en blijven
        echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form>";

        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='post' onsubmit='return checkFactuur();'>
                    <input type='hidden' value='" . $ID . "' name='ID'>
                    <input type='hidden' value='" . $klant . "' id='checkKlant'>
                    <label><input type='date' name='factuurdatum'></label>
                <input type='submit' value='Factureer' name='factuur'></form><br>";

        echo "<label>Ordernr: " . $ID . "</label>";
        if ($kortingklant !== 0) {
            echo "<label>Klantenkorting: " . $kortingklant . "%</label>";
        }
        //klant selecteren (optioneel)
        echo "<br><form action='" . $_SERVER['PHP_SELF'] . "' method='post'>"; ?>
        <label>Klant:
            <select name='klant'>
                <option class="placeholder" value="">--Selecteer een klant--</option>
                <?php

                $sql = 'SELECT ID  , Naam FROM klant;';
                $result = $conn->query($sql);
                if (isset($result)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($klant === $row['Naam']) {
                            echo "<option class='klant'  value='" . $row['ID'] . "' selected >" . $row['Naam'] . "</option>";
                        } else {
                            echo "<option class='klant'  value='" . $row['ID'] . "'>" . $row['Naam'] . "</option>";
                        }
                    }
                }
            }
            echo "</select></label><label>Filter: <input type='text' id='klantFilter'></label>
                    <button type='button' onclick='filter(\"klant\");'>Ok</button>
                    <button type='button' onclick='stopFilter(\"klant\");'>Stop filter</button>";
            echo "<input type='hidden' value='" . $ID . "' name='ID'>
                    <input type='submit' value='Bewaar klant' name='bewaarKlant'></form><br>";

            //nieuw item


            //berekenen van hoeveelheidskorting
            //bestelling al eerder aangemaakt: zal normaliter nu altijd doorlopen worden
            $hoeveelheidsKorting = 0;
            $aantalFlessen = 0;
            $totaalIncl = 0;
            $sql = 'SELECT i.Aantal as Aantal, v.Prijs as Prijs
            FROM item i 
            JOIN bestelling b ON b.ID = i.BestelID
            JOIN voorraad v ON v.ID = i.ProductID
            WHERE b.ID = ' . $ID . ';';
            $result = $conn->query($sql);
            if (isset($result->num_rows)) {
                if ($result->num_rows > 0) {
                    //er zijn reeds items toegevoegd aan deze bestelling
                    while ($row = $result->fetch_assoc()) {
                        if ($row['Aantal'] !== null) {
                            $aantalFlessen += $row['Aantal'];
                            $totaalIncl += ($row['Aantal'] * $row['Prijs']);
                        }
                    }
                    if ($totaalIncl >= 300) {
                        $hoeveelheidsKorting = 10;
                    } else if ($aantalFlessen >= 12) {
                        $hoeveelheidsKorting = 5;
                    }

                    //volledig overzicht bestelling zodat de gebruiker kan kiezen om desgewenst onmiddellijk tot facturatie over te gaan
                    echo "<table><thead><th>Code</th><th>Omschrijving</th><th>Aantal</th><th>Prijs excl.BTW</th><th>Korting</th>
                                <th>Prijs na korting</th><th>Prijs incl.BTW</th></thead>";
                    //globaal overzicht bestelling

                    if ($klant !== "") {
                        $sql = 'SELECT v.ID as Artikelnummer, CONCAT_WS(" ", a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, 
                i.Aantal as Aantal, v.Prijs as Prijs, v.Korting as ProductKorting, k.Korting as KlantKorting,i.Korting as manKorting
            FROM item i JOIN bestelling b ON i.BestelID = b.ID
            JOIN voorraad v ON i.ProductID = v.ID
            JOIN appellatie a ON v.AppellatieID = a.ID
            JOIN domein d ON v.DomeinID = d.ID
            JOIN klant k ON b.KlantID = k.ID
            JOIN soort s ON v.SoortID = s.ID
            JOIN volume vol ON v.VolumeID = vol.ID
            WHERE b.ID = ' . $ID . ';';
                    } else {
                        $sql = 'SELECT v.ID as Artikelnummer, CONCAT_WS(" ", a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, 
                i.Aantal as Aantal, v.Prijs as Prijs, v.Korting as ProductKorting,i.Korting as manKorting
            FROM item i JOIN bestelling b ON i.BestelID = b.ID
            JOIN voorraad v ON i.ProductID = v.ID
            JOIN appellatie a ON v.AppellatieID = a.ID
            JOIN domein d ON v.DomeinID = d.ID
            JOIN soort s ON v.SoortID = s.ID
            JOIN volume vol ON v.VolumeID = vol.ID
            WHERE b.ID = ' . $ID . ';';
                    }
                    $result = $conn->query($sql);
                    if (isset($result->num_rows)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                //prijs exclusief BTW en zonder kortingen
                                $prijsExcl = round(($row['Prijs'] / 1.21), 2, PHP_ROUND_HALF_DOWN);
                                $korting = $hoeveelheidsKorting;
                                if ($klant !== "") {
                                    //mogelijks is er klantenkorting
                                    if ($row['KlantKorting'] > $row['ProductKorting'] && $row['KlantKorting'] > $hoeveelheidsKorting) {
                                        $korting = $row['KlantKorting'];
                                    } else if ($row['ProductKorting'] > $hoeveelheidsKorting) {
                                        $korting = $row['ProductKorting'];
                                    }
                                } else {
                                    //er is nog geen klant geselecteerd
                                    if ($row['ProductKorting'] > $hoeveelheidsKorting) {
                                        $korting = $row['ProductKorting'];
                                    }
                                }
                                //pas eventueel manuele korting toe
                                if ($korting < $row['manKorting']) $korting = $row['manKorting'];
                                $prijsNaKorting = round(($prijsExcl * (100 - $korting) / 100), 2, PHP_ROUND_HALF_DOWN);
                                $prijsIncl = round(($prijsNaKorting * 1.21), 2, PHP_ROUND_HALF_DOWN);
                                if (empty($row['Aantal'])) {
                                    echo "<tr><td>" . $row['Artikelnummer'] . "</td><td>" . $row['Product'] . "</td>
                                        <td class='checkAantal'>nog geen aantal ingegeven</td>";
                                } else {
                                    echo "<tr class='checkItem'><td>" . $row['Artikelnummer'] . "</td><td>" . $row['Product'] . "</td>
                                        <td>" . $row['Aantal'] . " stuks</td>";
                                }
                                echo " <td>&euro; " . number_format($prijsExcl, 2, ',', '.') . "</td><td>" . $korting . "%</td>
                            <td>&euro; " . number_format($prijsNaKorting, 2, ',', '.') . "</td>
                            <td>&euro; " . number_format($prijsIncl, 2, ',', '.') . "</td>
            <td>
                <form action='items/item.php' method='post'>
                    <input type='hidden' value='" . $ID . "' name='bestelID'>
                    <input type='hidden' value='" . $row['Aantal'] . "' name='aantal'>
                    <input type='hidden' value='" . $row['manKorting'] . "' name='manKorting'>
                    <input type='hidden' value='" . $row['Artikelnummer'] . "' name='productID'>              
                    <input type='submit' value='aanpassen' name='sentAanpassen'>
                </form>
            </td>
            <td>
                <form action='" . $_SERVER['PHP_SELF'] . "' method='post'>
                    <input type='hidden' value='" . $ID . "' name='bestelID'>
                    <input type='hidden' value='" . $row['Aantal'] . "' name='aantal'>
                    <input type='hidden' value='" . $row['Artikelnummer'] . "' name='productID'>              
                    <input type='submit' value='verwijderen' name='del'>
                </form>
            </td>
         </tr>";
                                //schrijf vervolgens de waarden weg voor dit item
                                $sql = 'UPDATE item
                                SET PrijsExcl = '.$prijsExcl.',
                                PrijsNaKorting = '.$prijsNaKorting.',
                                PrijsIncl = '.$prijsIncl.',
                                KortingDefinitief = '.$korting.'
                                WHERE BestelID = '.$ID.'
                                AND ProductID = '.$row['Artikelnummer'].';';
                                $resultInsert = $conn->query($sql);
                            }
                            echo "</table>";
                        }
                    }
                } else {
                    //er zijn nog geen items toegevoegd aan deze bestelling
                    echo "<span>Nog geen items toegevoegd aan deze bestelling</span>";
                }
            }
            //nieuw item
            echo "<form action='items/item.php' method='post'><input type='hidden' value='" . $ID . "' name='ID'>
            <input type='submit' value='nieuw item' name='sent'></form>";
    }
    else{
        echo "<script>
                    if(confirm('Factuur gemaakt. Afdrukken?')){
            //afdrukken
            window.alert('kijk eens naar het probleem');
            window.open('pdfFactuur.php','factuur_NR_KlantNaam');
            }
            else{
                //enkel save pdf
                myWindow = window.open('pdfFactuurZonderAfdruk.php','factuur_NR_KlantNaam');
                myWindow.close();
    
            }
            window.location.href = '../facturen/index.php';
            </script>";
    }
    $conn->close();
    ?>

    </body>
</html>



