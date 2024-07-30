
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aanpassen appellatie</title>
    <link href="../shared/overall.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
include ('../shared/hoofdmenu_2.php');
?>
<hr>
<h1>Aanpassen appellatie</h1>
<?php
//terug naar overzicht
echo "<form action='index.php' method='post'><input type='submit' value='Ga terug'></form>";
//aanpassen nieuwe appellatie

$appellatie="";
$ID="";
if(isset($_POST['modify'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $appellatie = trim($_POST['appellatie']);
    $appellatie = str_replace('"','\"',$appellatie);
    $appellatie = str_replace("'","\'",$appellatie);
    $ID = trim($_POST['ID']);
    //aanpassen appellatie
    include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
    //globaal overzicht appellaties
    $sql = "UPDATE appellatie SET Naam = '".$appellatie."' WHERE ID = ".$ID.";";
    $conn->query($sql);
    $conn->close();
    header('Location: index.php');

}
else if(isset($_POST['sent'])&&$_SERVER['REQUEST_METHOD'] === 'POST') {
    $appellatie = trim($_POST['Naam']);
    $appellatie = str_replace('"','\"',$appellatie);
    $appellatie = str_replace("'","\'",$appellatie);
    $ID = trim($_POST['ID']);
}
echo "<form action=\"".$_SERVER['PHP_SELF']."\" method='post'>
        <label>Naam appellatie: </label>
        <input type='text' name='appellatie' value='".$appellatie."'>
         <input type=\"hidden\" value=\"" .$ID. "\" name=\"ID\">  
        <input type='submit' value='Aanpassen' name='modify'>
      </form>";
?>

</body>
</html>