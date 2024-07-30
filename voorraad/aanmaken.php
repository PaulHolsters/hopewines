<?php

//product aanmaken op basis van wat de gebruiker heeft meegegeven hieronder
if(isset($_POST['add'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $appellatieID = trim($_POST['appellatie']);
    $domeinID = trim($_POST['domein']);
    $volumeID = trim($_POST['volume']);
    $soortID = trim($_POST['soort']);
    $naam = trim($_POST['naam']);
    $naam = str_replace('"','\"',$naam);
    $naam = str_replace("'","\'",$naam);
    $korting = trim($_POST['korting']);
    $jaar = trim($_POST['jaar']);
    if(empty($jaar)){
        $jaar="NULL";

    }
    $aantal = trim($_POST['aantal']);
    if(empty($aantal)){
        $aantal="NULL";
    }
    $prijs = trim($_POST['prijs']);
    //invoeren van nieuw product
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    $sql = "INSERT INTO voorraad VALUES(NULL,".$appellatieID.", '".$naam."',".$jaar.",".$domeinID.",".$volumeID.",".$soortID.",".$korting.",".$aantal.",0,0,".$prijs.");";
    $conn->query($sql);
    $conn->close();
}
?>

<!doctype html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Aanmaken product</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
        <script src="../shared/overall.js"></script>
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Nieuw product</h1>
    <?php
    //terug naar overzicht
    echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form><br>
<form action='".$_SERVER['PHP_SELF']."' method='post' onsubmit='return checkFormulier2();'>";?>


            <label>Appellatie:
                <select name='appellatie'><option class="placeholder">--Selecteer een appellatie--</option>
                    <?php
            include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
            $sql = 'SELECT ID  , Naam FROM appellatie;';
            $result = $conn->query($sql);
            if(isset($result)){
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                    $appellatieID = $row["ID"];
                    $appellatie=$row["Naam"];
                    echo "<option class='appellatie' value='".$appellatieID."'>".$appellatie."</option>";
                    }
                }
            }

            echo "</select></label><label>Filter: <input type='text' id='appellatieFilter'></label>
                    <button type='button' onclick='filter(\"appellatie\");'>Ok</button>
                    <button type='button' onclick='stopFilter(\"appellatie\");'>Stop filter</button><br>
            
            <label>Naam (optioneel): </label>
            <input type='text' name='naam'><br>
            
            <label>Jaar (optioneel): </label>
            <input type='number' min='1500' max='3000' name='jaar'><br>
            
            
            <label>Domein: </label>
            <select name='domein'>";
            ?>
            <option class="placeholder">--Selecteer een domein--</option>
           <?php $sql = 'SELECT ID  , Naam FROM domein;';
            $result = $conn->query($sql);
            if(isset($result)){
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                    $domeinID = $row["ID"];
                    $domein=$row["Naam"];
                    echo "<option class='domein'   value='".$domeinID."'>".$domein."</option>";
                    }
                }
            }

            echo "</select><label>Filter: <input type='text' id='domeinFilter'></label><button type='button' onclick='filter(\"domein\");'>Ok</button>
                    <button type='button' onclick='stopFilter(\"domein\");'>Stop filter</button><br>
            
            <label>Korting: 
            <input type='number' min='0' value='0' max='100' name='korting' required>%</label><br>
            
            <label>Prijs inclusief BTW: &euro; <input type='number' min='0.01' step='0.01'  name='prijs' required></label><br>
            
            <label>Aantal: 
            <input type='number' name='aantal'>  stuks</label><br>";

            echo "<label>Volume: </label>
            <select name='volume'>";
            $sql = 'SELECT ID  , Naam FROM volume ORDER BY ID;';
            $result = $conn->query($sql);
            if(isset($result)){
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                    $volumeID = $row["ID"];
                    $volume=$row["Naam"];
                    echo "<option  value='".$volumeID."'>".$volume."</option>";
                    }
                }
            }
            echo "</select><br>";

            echo "<label>Soort: </label>
            <select name='soort'>";
            $sql = 'SELECT ID , Naam FROM soort;';
            $result = $conn->query($sql);
            if(isset($result)){
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                    $soortID = $row["ID"];
                    $soort=$row["Naam"];
                    echo "<option  value='".$soortID."'>".$soort."</option>";
                    }
                }
            }
            $conn->close();
            echo "</select><br>
            
            <input type='submit' value='Invoeren' name='add'>
          </form>";
    ?>
    </body>
</html>
