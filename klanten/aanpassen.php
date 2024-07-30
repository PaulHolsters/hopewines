<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aanpassen klant</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
include ('../shared/hoofdmenu_2.php');
?>
<hr>
<h1>Aanpassen klant</h1>
<?php
//terug naar overzicht
echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form><br>";
//aanpassen klant

$ID="";
include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
if(isset($_POST['modify'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {

    $klant = trim($_POST['klant']);
    $klant = str_replace('"','\"',$klant);
    $klant = str_replace("'","\'",$klant);

    $straat = trim($_POST['straat']);
    $straat = str_replace('"','\"',$straat);
    $straat = str_replace("'","\'",$straat);

    $gemeente = trim($_POST['gemeente']);
    $gemeente = str_replace('"','\"',$gemeente);
    $gemeente = str_replace("'","\'",$gemeente);

    $BTWnr = trim($_POST['BTWnr']);
    $BTWnr = str_replace('"','\"',$BTWnr);
    $BTWnr = str_replace("'","\'",$BTWnr);

    $korting = trim($_POST['korting']);

    $email1 = trim($_POST['email_1']);
    $email1 = str_replace('"','\"',$email1);
    $email1 = str_replace("'","\'",$email1);

    $email2 = trim($_POST['email_2']);
    $email2 = str_replace('"','\"',$email2);
    $email2 = str_replace("'","\'",$email2);

    $GSM = trim($_POST['GSM']);
    $GSM = str_replace('"','\"',$GSM);
    $GSM = str_replace("'","\'",$GSM);

    $tel = trim($_POST['tel']);
    $tel = str_replace('"','\"',$tel);
    $tel = str_replace("'","\'",$tel);

    $contact = trim($_POST['contact']);
    $contact = str_replace('"','\"',$contact);
    $contact = str_replace("'","\'",$contact);

    $ID = trim($_POST['ID']);


    $sql = "UPDATE klant SET Naam = '".$klant."', Straat = '".$straat."', Gemeente = '".$gemeente."', BTWNR = '".$BTWnr."', Korting = '".$korting."',
                             Email1 = '".$email1."', Email2 = '".$email2."', GSM = '".$GSM."', Telefoon = '".$tel."', Contactpersoon = '".$contact."' 
                             WHERE ID = ".$ID.";";
    $conn->query($sql);
    echo "Aanpassingen uitgevoerd.<br>";
}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID = trim($_POST['ID']);
}
//toon appellaties op basis van filter
$sql = 'SELECT Naam, Straat, Gemeente, Korting, BTWNR, Contactpersoon, Email1, Email2, GSM, Telefoon  FROM klant
                WHERE ID = '.$ID.';';
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'>
            <label>Naam klant: </label>
            <input type='text' name='klant' value='".$row['Naam']."' required><br>
            
            <label>Straat + nr: </label>
            <input type='text' name='straat' value='".$row['Straat']."' required><br>
            
            <label>Postcode + gemeente: </label>
            <input type='text' name='gemeente' value='".$row['Gemeente']."' required><br>
            
            <label>Korting: %</label>
            <input type='number' min='0' max='100' name='korting' value='".$row['Korting']."' required><br>
            
            <label>BTW nummer:</label>
            <input type='text' name='BTWnr' value='".$row['BTWNR']."'><br>
            
            <label>Contactpersoon: </label>
            <input type='text' name='contact' value='".$row['Contactpersoon']."'><br>
            
            <label>E-mail 1: </label>
            <input type='text' name='email_1' value='".$row['Email1']."'><br>
            
            <label>E-mail 2: </label>
            <input type='text' name='email_2' value='".$row['Email2']."'><br>
            
            <label>GSM: </label>
            <input type='text' name='GSM' value='".$row['GSM']."'><br>
            
            <label>Telefoon: </label>
            <input type='text' name='tel' value='".$row['Telefoon']."'><br>
            
            <input type='hidden' name='ID' value='$ID'>
            
            <input type='submit' value='Aanpassen' name='modify'>
          </form>";
    }
}
$conn->close();
?>

</body>
</html>