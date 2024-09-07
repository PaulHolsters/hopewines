<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Klanten</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php
    include('../shared/hoofdmenu_2.php');
    ?>
    <h1>Overzicht</h1>
    <?php
    $filter = false;
    $text = "";
    if (isset($_POST['ok']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $filter = true;
        $text = trim($_POST['filter']);
        $text = str_replace('"', '\"', $text);
    }
    echo "<table><thead>
<tr><th class='no-border' colspan='3'>";
    if ($filter) {
        echo "<form  action=\"" . $_SERVER['PHP_SELF'] . "\" method='post'>
                <label>filter: </label>
                <input type='text' name='filter' value='" . $text . "'>
                <input type='submit' value='Ok' name='ok'>
              </form>";
        echo "<form  action=\"" . $_SERVER['PHP_SELF'] . "\" method='post'><input type='submit' value='Stop filter'></form>";
    } else {
        echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method='post'><label>filter: </label>
<input type='text' name='filter'><input type='submit' value='Ok' name='ok'></form>";
    }
    echo "</th><th class='no-border align-right'>";
    echo "<form action='aanmaken.php' method='post'><input type='submit' value='maak klant' name='sent'></form>";
    echo "</th></tr>
<tr><th>Klantnummer</th><th class='w-250'>Naam</th><th class='w-250'>Gemeente</th><th>Korting</th></tr>
</thead>";
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    if ($filter) {
        //toon appellaties op basis van filter
        $sql = 'SELECT ID, Naam, Gemeente, Korting FROM klant
                WHERE Naam like "%' . $text . '%";';
        $result = $conn->query($sql);
        if (isset($result->num_rows)) {
            if ($result->num_rows > 0) {
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>" . $row['ID'] . "</td><td>" . $row['Naam'] . "</td><td>" . $row['Gemeente'] . "</td><td>" . $row['Korting'] . "%</td>
                    <td>
                        <form action='aanpassen.php' method='post'>
                            <input type=\"hidden\" value=\"" . $row['ID'] . "\" name=\"ID\">
                            <input type='submit' value='aanpassen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='verwijderen.php' method='post'>
                            <input type=\"hidden\" value=\"" . $row['ID'] . "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='sent'>
                        </form>
                    </td>
                 </tr>";
                }
                echo "</tbody>";
            } else {
                echo "<tfoot><tr><td colspan='4'>Nog geen klanten ingevoerd.</td></tr></tfoot>";
            }
        }
    } else {
        //globaal overzicht appellaties
        $sql = 'SELECT ID, Naam, Gemeente, Korting FROM klant;';
        $result = $conn->query($sql);
        if (isset($result->num_rows)) {
            if ($result->num_rows > 0) {
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>" . $row['ID'] . "</td><td>" . $row['Naam'] . "</td><td>" . $row['Gemeente'] . "</td><td>" . $row['Korting'] . "%</td>
                    <td>
                        <form action='aanpassen.php' method='post'>
                            <input type=\"hidden\" value=\"" . $row['ID'] . "\" name=\"ID\">
                            <input type='submit' value='aanpassen' name='sent'>
                        </form>
                    </td>
                    <td>
                        <form action='verwijderen.php' method='post'>
                            <input type=\"hidden\" value=\"" . $row['ID'] . "\" name=\"ID\">               
                            <input type='submit' value='verwijderen' name='sent'>
                        </form>
                    </td>
                 </tr>";
                }
                echo "</tbody>";
            } else {
                echo "<tfoot><tr><td colspan='4'>Nog geen klanten ingevoerd.</td></tr></tfoot>";
            }
        }

    }
    $conn->close();
    echo "</table>";
    ?>

</body>
<script>
    document.getElementsByTagName('a')[2].classList.add('active')
</script>
</html>



