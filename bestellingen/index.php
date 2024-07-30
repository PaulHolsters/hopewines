<?php
//better work with login and a clear session
//dan hoeft je maar één keer een connectie aan te maken en 1 keer ze te sluiten
include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
if(isset($_POST['verwijderen'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID = $_POST['ID'];
    //alle items wissen en de voorraad aanpassen
    $sql = "SELECT ProductID, Aantal FROM item WHERE BestelID = ".$ID.";";
    $result = $conn->query($sql);
    if(isset($result->num_rows)){
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sql = "DELETE FROM item WHERE BestelID = ".$ID." AND ProductID = ".$row['ProductID'].";";
                $result2 = $conn->query($sql);
                if($result2){
                    if(!empty($row['Aantal'])){
                        $sql = "UPDATE voorraad SET Aantal = Aantal + ".$row['Aantal']." WHERE ID = ".$row['ProductID'].";";
                        $result2 = $conn->query($sql);
                    }
                }

            }
        }
    }
    //de bestelling zelf wissen
    $sql = "DELETE FROM bestelling WHERE ID = ".$ID.";";
    $result = $conn->query($sql);
}
?>
<!doctype html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Bestellingen</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
        <script src="../shared/overall.js"></script>
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Bestellingen</h1>
    <?php
    //aanmaken nieuwe bestelling
    echo "<form action='bestelling.php' method='post'><input type='submit' value='maak bestelling' name='nieuw'></form><br>";

    //filteren op naam
    $filter = false;
    $text = "";
    if(isset($_POST['ok'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
        $filter = true;
        $text = trim($_POST['filter']);
        $text = str_replace('"','\"',$text);
        $text = str_replace("'","\'",$text);
    }
    if($filter){
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'>
                <label>filter: </label>
                <input type='text' name='filter' value='".$text."'>
                <input type='submit' value='Ok' name='ok'>
              </form>";
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'><input type='submit' value='Stop filter'></form><br>";
    }
    else{
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'><label>filter: </label><input type='text' name='filter'><input type='submit' value='Ok' name='ok'></form><br>";
    }

    //overzicht bestellingen met naam
    echo "<table><thead><th>Ordernummer</th><th>Klant</th><th>Klantenkorting</th><th>Datum</th></thead>";
    if($filter){
        //toon bestellingen op basis van filter
        if($text !=='geen klant') {
            $sql = 'SELECT b.ID as Nr, k.Naam as Klant, k.Korting as Korting, DATE_FORMAT(b.Datum,"%e/%m/%Y") as Datum
                FROM bestelling b JOIN klant k ON b.KlantID = k.ID
                WHERE k.Naam LIKE "%' . $text . '%" 
                AND b.Factuurnummer IS NULL ORDER BY Klant, Datum;';
            $result = $conn->query($sql);
            if (isset($result->num_rows)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row['Nr'] . "</td><td>" . $row['Klant'] . "</td><td>" . $row['Korting'] . "%</td><td>" . $row['Datum'] . " </td>
                    <td>
                        <form action='".$_SERVER['PHP_SELF']."' method='post'>
                            <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">  
                            <input type='submit' value='verwijderen' name='verwijderen'>
                        </form>
                    </td>
                    <td>
                        <form action='bestelling.php' method='post'>
                            <input type=\"hidden\" value=\"" . $row['Korting']  . "\" name=\"korting\"> 
                            <input type=\"hidden\" value=\"" . $row['Nr'] . "\" name=\"ID\">               
                            <input type='submit' value='details' name='aanpassen'>
                        </form>
                    </td>
                 </tr>";
                    }
                }
            }
        }
        else{
            $sql = 'SELECT ID as Nr,KlantID, DATE_FORMAT(Datum,"%e/%m/%Y") as Datum
                FROM bestelling WHERE KlantID IS NULL AND b.Factuurnummer IS NULL  ORDER BY Datum;';
            $result = $conn->query($sql);
            if(isset($result->num_rows)){
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row['Nr']."</td><td>nog geen klant toegewezen</td><td> -- </td><td>".$row['Datum']."</td>
                                <td>
                                    <form action='".$_SERVER['PHP_SELF']."' method='post'>
                                        <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">               
                                        <input type='submit' value='verwijderen' name='verwijderen'>
                                    </form>
                                </td>
                                <td>
                                    <form action='bestelling.php' method='post'>
                                        <input type=\"hidden\" value=\"\" name=\"korting\"> 
                                        <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">               
                                        <input type='submit' value='details' name='aanpassen'>
                                    </form>
                                </td>
                             </tr>";
                    }
                }
            }
        }
        echo "</table>";
    }
    else{
        //eerst de naamloze bestellingen
        $sql = 'SELECT ID as Nr, KlantID, DATE_FORMAT(Datum,"%e/%m/%Y") as Datum
                FROM bestelling WHERE KlantID IS NULL AND b.Factuurnummer IS NULL ORDER BY Datum;';
        $result = $conn->query($sql);
        if(isset($result->num_rows)){
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['Nr']."</td><td>nog geen klant toegewezen</td><td> -- </td><td>".$row['Datum']."</td>
                    <td>
                        <form action='".$_SERVER['PHP_SELF']."' method='post'>
                            <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='verwijderen'>
                        </form>
                    </td>
                    <td>
                        <form action='bestelling.php' method='post'>
                            <input type=\"hidden\" value=\"\" name=\"korting\"> 
                            <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">               
                            <input type='submit' value='details' name='aanpassen'>
                        </form>
                    </td>
                 </tr>";
                }
            }
        }
        //niet naamloze bestellingen
        $sql = 'SELECT b.ID as Nr, k.Naam as Klant,k.Korting as Korting, DATE_FORMAT(b.Datum,"%e/%m/%Y") as Datum
                FROM bestelling b JOIN klant k ON b.KlantID = k.ID WHERE b.Factuurnummer IS NULL ORDER BY Klant, Datum;';
        $result = $conn->query($sql);
        if(isset($result->num_rows)){
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['Nr']."</td><td>".$row['Klant']."</td><td>" . $row['Korting'] . "%</td><td>".$row['Datum']."</td>
                    <td>
                        <form action='".$_SERVER['PHP_SELF']."' method='post'>
                            <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='verwijderen'>
                        </form>
                    </td>
                    <td>
                        <form action='bestelling.php' method='post'>
                            <input type=\"hidden\" value=\"" . $row['Korting']  . "\" name=\"korting\"> 
                            <input type=\"hidden\" value=\"" .$row['Nr']. "\" name=\"ID\">               
                            <input type='submit' value='details' name='aanpassen'>
                        </form>
                    </td>
                 </tr>";
                }
            }
        }
        echo "</table>";
    }
    $conn->close();
    ?>
    </body>
</html>



