<!doctype html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Verwijderen appellatie</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Verwijderen appellatie</h1>
    <?php
    //terug naar overzicht
    echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form>";
    //verwijderen appellatie
    $ID="";
    if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
        $ID = trim($_POST['ID']);
        //aanpassen appellatie
        include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
        //globaal overzicht appellaties
        $sql = "DELETE FROM appellatie WHERE ID = ".$ID.";";
        $conn->query($sql);
        $conn->close();
        header('Location: index.php');
    }
    ?>
    </body>
</html>
