<?php
if(isset($_POST['add'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $appellatie = trim($_POST['appellatie']);
    $appellatie = str_replace('"','\"',$appellatie);
    $appellatie = str_replace("'","\'",$appellatie);
    //invoeren van nieuwe appellatie in databank
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    //globaal overzicht appellaties
    $sql = "INSERT INTO appellatie VALUES(NULL,'".$appellatie."');";
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
        <title>Aanmaken appellatie</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Nieuwe appellatie</h1>
    <?php
    //terug naar overzicht
    echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form>";
    //invoeren nieuwe appellatie
    echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'><label>Naam appellatie: </label><input type='text' name='appellatie' required><input type='submit' value='Invoeren' name='add'></form>";
    ?>
    </body>
</html>
