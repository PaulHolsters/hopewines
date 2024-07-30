
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aanpassen domein</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
include ('../shared/hoofdmenu_2.php');
?>
<hr>
<h1>Aanpassen domein</h1>
<?php
//terug naar overzicht
echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form>";
//aanpassen domein

$domein="";
$ID="";
if(isset($_POST['modify'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $domein = trim($_POST['domein']);
    $domein = str_replace('"','\"',$domein);
    $domein = str_replace("'","\'",$domein);
    $ID = trim($_POST['ID']);
    //aanpassen appellatie
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    //globaal overzicht appellaties
    $sql = "UPDATE domein SET Naam = '".$domein."' WHERE ID = ".$ID.";";
    $conn->query($sql);
    $conn->close();
    header('Location: index.php');

}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $domein = trim($_POST['Naam']);
    $domein = str_replace('"','\"',$domein);
    $domein = str_replace("'","\'",$domein);
    $ID = trim($_POST['ID']);
}
echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'>
        <label>Naam domein: </label>
        <input type='text' name='domein' value='".$domein."'>
         <input type=\"hidden\" value=\"" .$ID. "\" name=\"ID\">  
        <input type='submit' value='Aanpassen' name='modify'>
      </form>";
?>

</body>
</html>