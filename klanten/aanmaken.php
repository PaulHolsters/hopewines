<?php
if(isset($_POST['add'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {

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

    //invoeren van nieuwe klant
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    $sql = "INSERT INTO klant VALUES(NULL,'".$klant."','".$straat."','".$gemeente."','".$korting."','".$BTWnr."','".$email1."','".$email2."','".$GSM."','".$contact."','".$tel."');";
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
        <title>Aanmaken klant</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Nieuwe klant</h1>
    <?php
    //terug naar overzicht
    echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form><br>";
    //invoeren nieuwe klant
    echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'>
            <label>Naam klant: </label>
            <input type='text' name='klant' required><br>
            
            <label>Straat + nr: </label>
            <input type='text' name='straat' required><br>
            
            <label>Postcode + gemeente: </label>
            <input type='text' name='gemeente' required><br>
            
            <label>Korting: %</label>
            <input type='number' min='0' value='0' max='100' name='korting' required><br>
            
            <label>BTW nummer:</label>
            <input type='text' name='BTWnr'><br>
            
            <label>Contactpersoon: </label>
            <input type='text' name='contact'><br>
            
            <label>E-mail 1: </label>
            <input type='text' name='email_1'><br>
            
            <label>E-mail 2: </label>
            <input type='text' name='email_2'><br>
            
            <label>GSM: </label>
            <input type='text' name='GSM'><br>
            
            <label>Telefoon: </label>
            <input type='text' name='tel'><br>
            
            <input type='submit' value='Invoeren' name='add'>
          </form>";
    ?>
    </body>
</html>
