<!doctype html>
<html lang="nl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Domeinen</title>
        <link href="../shared/overall.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <?php
    include ('../shared/hoofdmenu_2.php');
    ?>
    <hr>
    <h1>Domeinen</h1>
    <?php
    //aanmaken nieuw domein
    echo "<form action='aanmaken.php' method='post'><input type='submit' value='maak domein' name='sent'></form><br>";

    //filteren op naam
    $filter = false;
    $text = "";
    if(isset($_POST['ok'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
        $filter = true;
        $text = trim($_POST['filter']);
        $text = str_replace('"','\"',$text);
    }
    if($filter){
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'><label>filter: </label><input type='text' name='filter' value='".$text."'><input type='submit' value='Ok' name='ok'></form>";
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'><input type='submit' value='Stop filter'></form><br>";
    }
    else{
        echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'><label>filter: </label><input type='text' name='filter'><input type='submit' value='Ok' name='ok'></form><br>";
    }

    //overzicht domeinen
    echo "<table><th>Volgnummer</th><th>Domein</th>";
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    if($filter){
        //toon appellaties op basis van filter
        $sql = 'SELECT ID, Naam FROM domein
                WHERE Naam like "%'.$text.'%";';
        $result = $conn->query($sql);
        if(isset($result->num_rows)){
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>".$row['ID']."</td><td>".$row['Naam']."</td>
                    <td>
                        <form action='aanpassen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">
                            <input type=\"hidden\" value=\"" .$row['Naam']. "\" name=\"Naam\">
                            <input type='submit' value='aanpassen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='verwijderen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['ID']. "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='sent'>
                        </form>
                    </td>
                 </tr>";
                }
            }
        }
        else{
            echo "Nog geen domeinen ingevoerd.";
        }
    }
    else{
        //globaal overzicht appellaties
        $sql = 'SELECT ID, Naam FROM domein;';
        $result = $conn->query($sql);
        if(isset($result->num_rows)){
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>".$row['ID']."</td><td>".$row['Naam']."</td>
                    <td>
                        <form action='aanpassen.php' method='post'>
                            <input type=\"hidden\" value=\"" .$row['Naam']. "\" name=\"Naam\">
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
                 </tr>";
                }
            }
        }
        else{
            echo "Nog geen domeinen ingevoerd.";
        }
    }
    $conn->close();
    echo "</table>";
    ?>
    </body>
</html>



