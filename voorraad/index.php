<!doctype html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Voorraad</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
        <script src="../shared/overall.js"></script>
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Voorraad</h1>
    <?php
    //aanmaken nieuw product
    echo "<form action='aanmaken.php' method='post'><input type='submit' value='maak product' name='sent'></form><br>";

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

    //overzicht domeinen
    echo "<table><thead><th>Nr</th><th>Omschrijving</th><th>Prijs incl.BTW</th><th>Aantal</th><th>Proefflessen</th><th>Onverkoopbaar</th><th>Korting</th></thead>";
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    if($filter){
        //toon producten op basis van filter
        $sql = 'SELECT v.ID as ID, CONCAT_WS(" ", a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, Prijs, Aantal, Proef, Onverkoopbaar,Korting
                FROM voorraad v JOIN appellatie a ON v.AppellatieID = a.ID
                JOIN domein d ON v.DomeinID = d.ID
                JOIN volume vol ON v.VolumeID = vol.ID
                JOIN soort s ON v.SoortID = s.ID
                WHERE a.Naam LIKE "%'.$text.'%" 
                OR d.Naam LIKE "%'.$text.'%" 
                OR a.Naam LIKE "%'.$text.'%"
                OR v.Naam LIKE "%'.$text.'%"
                OR Jaar LIKE "%'.$text.'%"
                OR s.Naam LIKE "%'.$text.'%"
                OR vol.Naam LIKE "%'.$text.'%";';
        $result = $conn->query($sql);
        if(isset($result->num_rows)){
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['Aantal']===null){
                        echo "<tr><td>".$row['ID']."</td><td>".$row['Product']."</td>
                                  <td>&euro; ".number_format($row['Prijs'],2,',','.')."</td><td>nog geen aantal ingevoerd</td>
                                  <td>".$row['Proef']." stuks</td><td>".$row['Onverkoopbaar']." stuks</td>
                                  <td>".$row['Korting']."%</td>";
                    }
                    else{
                        echo "<tr><td>".$row['ID']."</td><td>".$row['Product']."</td>
                                  <td>&euro; ".number_format($row['Prijs'],2,',','.')."</td>
                                  <td>".$row['Aantal']." stuks</td><td>".$row['Proef']." stuks</td>
                                  <td>".$row['Onverkoopbaar']." stuks</td><td>".$row['Korting']."%</td>";
                    }
                    echo "<td>
                        <form action='aanpassen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">
                            <input type='submit' value='aanpassen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='verwijderen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='proef.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='proefflessen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='onverkoopbaar.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='onverkoopbaar' name='sent'>
                        </form>
                    </td>
                 </tr>";
                }
            }
        }
        else{
            echo "Nog geen producten ingevoerd.";
        }
    }
    else{
        //globaal overzicht producten
        $sql = 'SELECT v.ID as ID, CONCAT_WS(" ",a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, Prijs, Aantal,Proef, Onverkoopbaar, Korting
                FROM voorraad v JOIN appellatie a ON v.AppellatieID = a.ID
                JOIN domein d ON v.DomeinID = d.ID
                JOIN volume vol ON v.VolumeID = vol.ID
                JOIN soort s ON v.SoortID = s.ID';
        $result = $conn->query($sql);
        if(isset($result->num_rows)){
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($row['Aantal']===null){
                        echo "<tr><td>".$row['ID']."</td><td>".$row['Product']."</td>
                                    <td>&euro; ".number_format($row['Prijs'],2,',','.')."</td>
                                    <td>nog geen aantal ingevoerd</td><td>".$row['Proef']." stuks</td>
                                    <td>".$row['Onverkoopbaar']." stuks</td><td>".$row['Korting']."%</td>";
                    }
                    else{
                        echo "<tr><td>".$row['ID']."</td><td>".$row['Product']."</td>
                                <td>&euro; ".number_format($row['Prijs'],2,',','.')."</td>
                                <td>".$row['Aantal']." stuks</td><td>".$row['Proef']." stuks</td>
                                <td>".$row['Onverkoopbaar']." stuks</td><td>".$row['Korting']."%</td>";
                    }
                    echo "<td>
                        <form action='aanpassen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">
                            <input type='submit' value='aanpassen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='verwijderen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='proef.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='proefflessen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='onverkoopbaar.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='onverkoopbaar' name='sent'>
                        </form>
                    </td>
                 </tr>";
                }
            }
        }
        else{
            echo "Nog geen producten ingevoerd.";
        }
    }
    $conn->close();
    echo "</table>";
    ?>
    </body>
</html>



