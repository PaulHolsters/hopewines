<?php
session_start();
include('C:\Users\PC Gebruiker\PhpstormProjects\winedows\shared\dbconnect.php');
//laat hier alle werk gebeuren dat is dus
//stap 1: verwerk de bestelling
//stap2: PDF wordt gegenereerd
//stap3: sla pDf op in de juiste map
$factuurnr = "";//
$klantID = "";//
$klant = "";//
$straat = "";//
$gemeente = "";//
$btw = "";//
$factuurdatum = "";
$cadeau = false;//
$bericht = "Wijnpakket(ten) in geschenkverpakking";//
$creditnota = false;//
//info factuur => te halen uit sessie variabele
$bestelID = $_SESSION['bestelID'];
$sql = 'SELECT k.ID AS KlantID, k.Naam AS Klant, k.Straat AS Straat, k.Gemeente AS Gemeente,
            k.BTWNR AS BTWNR, b.Factuurnummer AS Factuurnummer, b.Cadeauverpakking AS Cadeauverpakking,
            DATE_FORMAT(b.Factuurdatum,"%e/%m/%Y") as Factuurdatum
            FROM klant k JOIN bestelling b ON b.KlantID = k.ID
            WHERE b.ID = '.$bestelID.';';

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $factuurnr = $row['Factuurnummer'];//
        $klantID = $row['KlantID'];//
        $klant = $row['Klant'];//
        $straat = $row['Straat'];//
        $gemeente = $row['Gemeente'];//
        $btw = $row['BTWNR'];//
        if(empty($btw)){
            $btw = "";
        }
        $cadeau = $row['Cadeauverpakking'];//
        /*
        if(!$cadeau){
            $bericht = "";
        }*/
        $factuurdatum = $row['Factuurdatum'];//
    }
}
$string = '
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>

    
    #logo{
        float: left;
        width: 330px;
    }
    
    .logoText{
        text-align: center;
        font-size: 18px;
        font-family: "Lucida handwriting",sans-serif;
        color: #6d15dd;
        margin: 15px 0;
    }
    
    #firmName{
        font-size: 32px;
        background-color: #7814dd;
        color: white;
        border-radius: 15px;
        border: 2px solid #423eff;
        margin: 0;
    }
    
    #adres{
    float: left;
    clear: left;
    width: 330px;
    }
    
    #firmInfoCol1{
    float: left;
    clear: left;
    width: 100px;
    }
    
    #firmInfoCol2{
    float: left;
    width: 230px;
    }
    
    #factuurInfoCol1{
    float: left;
    clear: left;
    width: 200px;
    }
    
    #factuurInfoCol2{
    float: left;
    width: 160px;
    }
    
    .space16{
    margin-top: 16px;
    }
    
    .space8{
    margin-top: 4px;
    }
    
    .boldUnderlined{
    font-weight: bold;
    text-decoration: underline;
    float: none;
    clear: both;
    margin-top: 20px;
    }
    
    #verkoopsvoorwaarden{
    margin-top: 40px;
    }
    
    .titel{
    font-weight: bold;
    text-decoration: underline;
    text-align: center;
    font-size: 11px;
    }
    
    .tekst{
    margin-top: 12px;
    font-size: 11px;
    }
    
    #infoKlant{
    float: right;
    clear:right;
    width: 280px;
    font-size: 18px;
    
    }
    
    #creditnota{
    float: right;
    width: 330px;
    font-size: 28px;
    text-align: right;
    }
    
    #tabel{
    float: none;
    clear: left;
    }
    
    
    
    .headRow{
        background-color: #5c28dd;
    }
    
    .headCells{
    color :white;
    text-align: center;
    font-size: 18px;
    }
    
    .even{
        background-color: #c8cddd;
    }
    
    .odd{
    background-color: #b2bcda;
    }
    
    .col1{
    width: 50px;
    padding-left: 6px;
    padding-right: 6px;
    }
    .col2{
    width: 420px;
    padding-left: 6px;
    padding-right: 6px;
    }
    .col3{
    width: 74px;
    padding-left: 6px;
    padding-right: 6px;
    }
    
    .col4{
    width: 124px;
    padding-left: 6px;
    padding-right: 6px;
    }
    
    .col5{
    width: 68px;
    padding-left: 6px;
    padding-right: 6px;
    }
    .col6{
    width: 120px;
    padding-left: 6px;
    padding-right: 6px;
    }
    
    .col1Detail{
    text-align: center;
    }
    
    .col2Detail{
    padding-left: 6px;
    }
    
    .col3Detail{
    text-align: right;
    padding-right: 6px;
    }
    
    .col4Detail{
    text-align: right;
    padding-right: 6px;
    }
    
    .col5Detail{
    text-align: right;
    padding-right: 6px;
    }
    
    .col6Detail{
    text-align: right;
    padding-right: 6px;
    }
    
    td{
    font-size: 18px;
    }
    
    .btw{
    margin-top: 12px;
    }
    
    .geschenk{
    font-weight: bold;
    margin-top: 8px;
    }
    
    .totaal1{
    padding-top: 12px;
    text-align: right;
    }
    
    .totaal{
    padding-top: 12px;
    text-align: right;
    padding-right: 6px;
    }
    
    .bedrag{
    padding-top: 24px;
    text-align: right;
    }
    
</style>

<body>
    
    <div id="logo">
        <div class="logoText">Boire bon</div>
        <div id="firmName" class="logoText">HOPEwines</div>
        <div class="logoText">C\'est vivre bien</div>
    </div>
    
    <div id="creditnota">CREDITNOTA</div>
    
    <div id="adres" class="space16">
        <div>Kiliaanstraat 60</div><div class="space8">2570 Duffel</div>
    </div>
    <div id="infoKlant">
        <div>'.$klant.'</div>
        <div>'.$straat.'</div>
        <div>'.$gemeente.'</div>
        <div class="btw">'.$btw.'</div>
        <div class="geschenk">'.$bericht.'</div>
    </div>
    <div id="firmInfoCol1" class="space16">
        <div>Tel</div><div class="space8">E-mail</div><div class="space8">BTW</div>
        <div class="space8">IBAN</div><div class="space8">BIC</div><div class="space8">Accijnsnr.</div>
    </div>
    <div id="firmInfoCol2">
        <div>0495/572358</div><div class="space8">willy.holsters@telenet.be</div><div class="space8">BE0688.799.176</div>
        <div class="space8">BE51 0689 0888 3862</div><div class="space8">GKCCBEBB</div><div class="space8">BE2H000687000</div>
    </div>
    <div class="boldUnderlined">Te vermelden bij betaling</div>
    <div id="factuurInfoCol1" class="space8">
        <div>Factuurnummer</div><div class="space8">Ordernummer</div><div class="space8">Klantnummer</div>
        <div class="space8">Datum</div>
    </div>
    <div id="factuurInfoCol2">
        <div>'.$factuurnr.'</div><div class="space8">'.$bestelID.'</div>
        <div class="space8">'.$klantID.'</div>
        <div class="space8">'.$factuurdatum.'</div>
    </div>
    <div id="tabel" class="space16">
        <table>';

$string .= '
            <tr class="headRow">
                <td class="headCells col1">Nr</td><td class="headCells col2">Product</td>
                <td class="headCells col3">Aantal</td><td class="headCells col4">Prijs per fles (excl. BTW)</td>
                <td class="headCells col5">Korting</td>
                <td class="headCells col6">Subtotaal</td>
            </tr>
        ';

$sql = 'SELECT v.ID AS Nr, CONCAT_WS(" ",a.Naam, d.Naam, v.Naam, Jaar, s.Naam, vol.Naam) AS Product, 
        PrijsExcl, KortingDefinitief as Korting, PrijsNaKorting, i.Aantal as Aantal
        FROM voorraad v JOIN item i ON i.ProductID = v.ID
        JOIN appellatie a ON v.AppellatieID = a.ID
        JOIN domein d ON v.DomeinID = d.ID
        JOIN volume vol ON v.VolumeID = vol.ID
        JOIN soort s ON v.SoortID = s.ID
        WHERE i.BestelID = '.$bestelID.';';
$rows = 0;
$som = 0;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows++;
        $som +=$row['PrijsExcl']*$row['Aantal'];
        if($rows%2===0){
            //oneven
            $string .= '
        <tr>
            <td class="col1Detail odd">'.$row['Nr'].'</td>
            <td class="col2Detail odd">'.$row['Product'].'</td>
            <td class="col3Detail odd">'.$row['Aantal'].'</td>
            <td class="col4Detail odd">'.number_format($row['PrijsExcl'],2,',','.').' &euro;</td>
            <td class="col5Detail odd">'.$row['Korting'].'%</td>
            <td class="col6Detail odd">'.number_format($row['PrijsExcl']*$row['Aantal'],2,',','.').' &euro;</td>
        </tr>';
        }
        else{
            $string .= '
        <tr>
            <td class="col1Detail even">'.$row['Nr'].'</td>
            <td class="col2Detail even">'.$row['Product'].'</td>
            <td class="col3Detail even">'.$row['Aantal'].'</td>
            <td class="col4Detail even">'.number_format($row['PrijsExcl'],2,',','.').' &euro;</td>
            <td class="col5Detail even">'.$row['Korting'].'%</td>
            <td class="col6Detail even">'.number_format($row['PrijsExcl']*$row['Aantal'],2,',','.').' &euro;</td>
        </tr>';
        }
    }
}
$bedragBTW = round($som*0.21,2);
$bedragIncl = $som+$bedragBTW;
$string .= '<tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="bedrag" colspan="2">Totaal Excl. BTW:</td>
                <td class="totaal bedrag">'.number_format($som,2,',','.').' &euro;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="totaal1" colspan="2">BTW 21%:</td>
                <td class="totaal">'.number_format($bedragBTW,2,',','.').' &euro;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="totaal1" colspan="2">Totaal Incl. BTW:</td>
                <td class="totaal">'.number_format($bedragIncl,2,',','.').' &euro;</td>
            </tr>
            </table>
            
            <div id="verkoopsvoorwaarden">
                <div class="titel">Verkoopsvoorwaarden</div>
                <div class="tekst">
Factuur te betalen binnen 30 dagen na factuurdatum. De goederen blijven eigendom van HOPEwines VOF tot na betaling van het
volledige factuurbedrag. Bij laattijdige betaling wordt een boete in rekening gebracht van 10% van het factuurbedrag met een
minimum van 40 &euro;.                    
                </div>
            </div>
            
            </div></body></html>';

require_once __DIR__ . '\vendor\autoload.php';

try {
        $mpdf = new \Mpdf\Mpdf();
    }
catch (\Mpdf\MpdfException $e) {

}
try {
    $mpdf->WriteHTML($string,\Mpdf\HTMLParserMode::DEFAULT_MODE);
}
catch (\Mpdf\MpdfException $e) {

}

try {
    $mpdf->Output();
    $mpdf->Output('D:\Users\PC Gebruiker\Documents\Hopewines\Facturen\klant_nrfac.pdf', \Mpdf\Output\Destination::FILE);
} catch (\Mpdf\MpdfException $e) {

}
