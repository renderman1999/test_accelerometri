<?php
include 'header.php';
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
?>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmgBxFdrb5atkmDq1VQREMJjGUrdHxxk8&callback=initMap">
</script>
<?php
$id_stazione = $_GET['id'];
//creo le query

$con=mysqli_connect("localhost","root","","test_accelerometri");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//query numero sensori KO
//$numero_sensori_ko = mysqli_query($con,"SELECT COUNT(DISTINCTROW num_sensore) as totale_sensori_ko , ris , id ,ref_value, value FROM result
//WHERE nome_stazione = '$id_stazione'
  //AND ris LIKE 'ERROR'
  //AND ref_value NOT LIKE 'mm'
   //AND DATE (date_import) = CURDATE()
//ORDER BY date_import
//");
$numero_sensori_ko = mysqli_query($con,"
SELECT COUNT(DISTINCTROW num_sensore) as totale_sensori_ko , ris , id ,ref_value, value FROM result
WHERE nome_stazione = '$id_stazione'
AND ris = 'ERROR'
AND (ref_value  NOT LIKE '%mm' AND value NOT LIKE '%mm')
AND DATE (date_import) = CURDATE()
ORDER BY date_import;");




//query numero sensori ok
$numero_sensori_ok = mysqli_query($con,"SELECT COUNT(DISTINCT num_sensore) as totale_sensori_ok , ris, id FROM result
WHERE DATE (date_import) = CURDATE()
AND nome_stazione = '$id_stazione'
  AND ris = 'OK'
ORDER BY date_import
");


//totale sensori ok ma con mm
$numero_sensori_ok_mm = mysqli_query($con,"
SELECT COUNT(DISTINCTROW num_sensore)as errore_mm  ,ris , id , ref_value, nome_stazione FROM result
WHERE nome_stazione = '$id_stazione'
AND ris = 'ERROR'
AND (ref_value LIKE '%mm%' AND value LIKE  '%mm%')
AND  DATE (date_import) = CURDATE()
GROUP BY nome_stazione;");


//query per anagrafica stazione
$anagrafica_stazione = mysqli_query($con, "SELECT  * FROM anagrafica
WHERE  Sigla = '$id_stazione'
ORDER BY Sigla ASC
");


//query per calcolare il numero dei sensori per ogni stazione
$sensori = mysqli_query($con, "SELECT COUNT(DISTINCT num_sensore) as totale_sensori , id FROM result
WHERE DATE (date_import) = CURDATE()
AND nome_stazione = '$id_stazione'
ORDER BY date_import");


$result = mysqli_query($con,"SELECT * FROM result
WHERE DATE (date_import) = CURDATE()
AND nome_stazione = '$id_stazione'
ORDER BY num_sensore
 
");

$row_sensori_ko = mysqli_fetch_array($numero_sensori_ko);
$row_sensori_ok_ma_con_errore_mm = mysqli_fetch_row($numero_sensori_ok_mm);
$row_sensori_ok = mysqli_fetch_array($numero_sensori_ok);
$row_anagrafica = mysqli_fetch_array($anagrafica_stazione);
$row_sensori = mysqli_fetch_array($sensori);
$row_specchietto = mysqli_fetch_array($result);
$row_sensori_ok_compresi_errore_mm = $row_sensori_ok['totale_sensori_ok'] + $row_sensori_ok_ma_con_errore_mm[0];
//print $row_sensori_ok_ma_con_errore_mm;
//$row_sensori_ok_compresi_errore_mm = isset($row_sensori_ok_ma_con_errore_mm['errore_mm']);
$calcolo_funzionalita = round($row_sensori_ok_compresi_errore_mm * 100 / $row_sensori['totale_sensori']);
?>


<div class="container-fluid " style="margin-top:  0px;margin-bottom:  50px" id="printableArea">

   <script>
        function initMap() {
            var stazione = {lat: <?php   echo $row_anagrafica['Latitudine']; ?>, lng: <?php  echo $row_anagrafica['Longitudine']; ?>};

            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: <?php   echo $row_anagrafica['Latitudine']; ?>  , lng: <?php  echo $row_anagrafica['Longitudine']; ?> },
                scrollwheel: false,
                zoom: 9
            });
            var marker = new google.maps.Marker({
                position: stazione,
                map: map,
                title: '<?php   echo $row_anagrafica['Sigla'];?>'
            });
        } // close function here
    </script>
    <div class="row z-depth-0">
    <div class="col-md-3 info-stazione " style="text-align: center;width: 100%;display: inline-table;">
        <div class='card' style="text-align: center;margin-bottom: 20px;margin-top: 20px;height: 400px;">
            <div class="card-body" style="transform: translateY( 5px)">
            <h2> <?php   echo $row_anagrafica['Sigla'];?></h2>
                <p>Sito: <?php   echo $row_anagrafica['Nome'];?></p>

<p>Regione: <?php   echo $row_anagrafica['Regione'];?></p>
<p>Provincia: <?php   echo $row_anagrafica['Provincia'];?></p>
<p>Comune: <?php   echo $row_anagrafica['Comune'];?></p>
<p  data-toggle="tooltip" title="Data del file Log"><i class="fa fa-clock-o fa-2x" aria-hidden="true" ></i><br><?php     echo $row_specchietto['data_file'];?></p>
            <p>

                <?php

                if ($row_anagrafica['Gestore'] == 'Lunitek') {
                    echo "<img class='logo-gestore' src='include/img/lunitek-logo.png'>";
                }

                elseif ($row_anagrafica['Gestore'] == 'GeoSig') {
                    echo "<img class='logo-gestore' src='include/img/geosig-logo.png'>";
                }

                elseif ($row_anagrafica['Gestore'] == 'CESIi') {
                    echo "<img class='logo-gestore' src='include/img/cesi-logo.png'>";
                }

                ?>


            </p>

        </div>
        </div>


    </div>
    <div class="col-md-6 mappa-anagrafica card" id="map"></div>
    <div class="col-md-3 riepilogo-stazione " style="text-align: center;width: 100%;display: inline-table;margin-top: 20px;">

        <div class="container" style="text-align: center;margin-bottom: 20px;margin-top:  0px;height: 400px;width: 100%">
            <div class="row" style="text-align: center">

        <div class="col-md-12 " style="width: 100%;background-color: lightgray">

           <h3 style="font-weight: bold;transform: translateY(15px)">TOT ACC.STAZIONE: <?php echo $row_sensori['totale_sensori'];?></h3>
            <p>File acquisito il:<br>
            <?php echo $row_specchietto['date_import'];?></p>
        </div>
            <div class="6 " style="width: 50% !important;background-color: #14A723;margin-top: 0px;height: 153px;">
                <p style="font-size: 4em;transform: translateY(25px)"><?php echo $row_sensori_ok['totale_sensori_ok'] + $row_sensori_ok_ma_con_errore_mm[0];?> OK</p>
            </div>
            <div class=" 6 " style="width: 50% !important;background-color: red;margin-top: 0px;height: 153px;">
                <p style="font-size: 4em;transform: translateY(25px)"><?php echo $row_sensori_ko['totale_sensori_ko'];?> KO</p>


            </div>
                <?php
                if ($calcolo_funzionalita == 100)
                    {
                echo "<div style='width: 100% !important;background-color: green;margin-top:  0px;height: 120px;'>";
                }
                elseif ($calcolo_funzionalita > 90){
                    echo "<div style='width: 100% !important;background-color: lightgreen;margin-top:  0px;height: 120px;'>";

                }
                elseif ($calcolo_funzionalita >= 80){
                    echo "<div style='width: 100% !important;background-color: orange;margin-top:  0px;height: 120px;'>";

                }
                elseif ($calcolo_funzionalita >= 70){
                    echo "<div style='width: 100% !important;background-color: orange;margin-top:  0px;height: 120px;'>";

                }  elseif ($calcolo_funzionalita <= 60){
                    echo "<div style='width: 100% !important;background-color: red;margin-top:  0px;height: 120px;'>";

                }
                elseif ($calcolo_funzionalita <=  0){
                    echo "<div class='animated flash' style='width: 100% !important;background-color: red;margin-top:  0px;height: 120px;'>";

                }
                ?>
                    <p style="text-align:center;font-size: 4em;transform: translateY(15px);color: whitesmoke">FUNZ. <?php echo $calcolo_funzionalita; ?> %</p>


                </div>
        </div>
        </div>

    </div>
    </div>
    <style>
        #map {
            height: 400px;
            width: 100%;
            margin-bottom: 20px;margin-top: 20px;
        }
    </style>
    <div >


    </div>


    <?php



   // echo $row_sensori['totale_sensori'];

    ?>


</div>

<div class="container-fluid" style="margin-top:   -30px;margin-bottom: 150px" id="printableArea">

    <div class="row">

        <?php


    while($row = mysqli_fetch_array($result))
    {
$risultato = $row['ris'];
        $ref_value_pulito = substr($row['ref_value'],0,6);
        $value_pulito = substr($row['value'],0,6);
$diff_value= floatval($value_pulito - $ref_value_pulito);
$diff_value = round($diff_value,5);
//genero la tabella con i risultati della query
        echo "
         <div class='col-md-2' style='margin-bottom: 20px'>
<!-- Card Wider -->
<div class='card wider'>

  <!-- Card image -->


  <!-- Card content -->
  <div class='card-body text-center' style='width: auto;display: inline-table;'>

    <!-- Title -->
      <div class='numero-sensore' style='background-color: white;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 50px;border: solid 1px lightgray'>
    <p class='card-title' style='font-size: 1.42em;transform: translatey(7px)'>POS N° ".  $row['pos']."</p>
  </div>
      <div class='freq-range' style='background-color: #CFCFCF;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 80px'>
          <p class='card-title' style='font-weight: lighter;horiz-align: center;transform: translatey(10px)'>Ref. Val.<br>
          <sub style='font-size: 1.5em;top: 12px'>  ".$row['ref_value']."</sub></p>
      </div>

      <div class='freq-range' style='background-color: #E5E5E5;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 80px'>
          <p class='card-title' style='font-weight: lighter !important;horiz-align: center;transform: translatey(10px)'>Val.<br>
          <sub style='font-size: 2em;top: 12px'> ".$row['value']."</sub></p>
      </div>

      <div class='freq-diff' style='background-color: #5373DB;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 40px;color: whitesmoke;'>
          <p class='card-title' style='font-weight: lighter !important;horiz-align: center;transform: translatey(6px);font-size: 1.5em'>Diff. $diff_value ";
//identifico se l'errore è perchè ci sono i mm
$stringa_g = "g";
$stringa_mm = "mm";
if (strpos($row['ref_value'],$stringa_g) == true)
{
    echo "g";
}
else {
    echo "mm";
}
          echo "</p>
      </div>
      
       ";
        if ( $risultato == 'OK' and $row['risolto_il'] == '') {
echo "<div class='freq-diff' style='background-color: #14A723;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 95px;color: whitesmoke;'>";
            echo "<p class='card-title' style='font-size:3.5em;font - weight: bold !important;horiz - align: center;transform: translatey(5px)'>OK</p>";
 }

elseif ($risultato == 'ERROR' and $row['risolto_il'] == '' and strpos($row['ref_value'],$stringa_mm) == true){
    echo "<div class='freq-diff' style='background-color: lightgreen;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 95px;color: whitesmoke;'>";
    echo "<p class='card-title' style='font-size:3.5em;font - weight: bold !important;horiz - align: center;transform: translatey(5px);color: black'>OK</p>";
}


 elseif ($risultato == 'ERROR' and $row['risolto_il'] == ''){
            echo "<div class='freq-diff' style='background-color: red;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 95px;color: black;'>";

            echo "<p class='card-title animated flash ' style='font-size:2.4em;font-weight: lighter !important;horiz - align: center;transform: translatey(13px);color: whitesmoke'>ERRORE</p><br>
<form action='' method='post' id='$row[nome_stazione]-$row[pos]'>
<input type='text' value='$row[pos]' name='pos_sensore' hidden name='pos'>
<input type='text' value='OK' hidden name='ris_aggiornata'>
<input type='text' value='$row[id]' hidden name='id_row_risolto'>
<input type='text' value='$row[risolto_il]' hidden name='data_risolto'>
<button type='submit' name='risolto' class='btn btn-xs btn-warning' style=' margin-top: -30px;height: 20px'><span style='position: relative; top: -10px'>Segna come risolto</span> </button></form> ";

        }

        elseif ($row['risolto_il'] != 'NULL'){
            echo "<div class='freq-diff' style='background-color: lightgreen;display: inline-table;left: 0px;right:0px;position: relative;width: 100%;min-height: 95px;color: black;'>";

            echo "<p class='card-title ' style='font-size:2.4em;font-weight: lighter !important;horiz - align: center;transform: translatey(5px);color: black'>OK</p><br>

<p name='risolto'  style=' margin-top: -30px;height: 20px'><span style='font-size:16px;position: relative; top: -5px'><i class='fa fa-clock-o' aria-hidden='true'></i>
 risolto il $row[risolto_il]</span> </p> ";
        }

  echo "</div > </div>


</div>
        </div>
        
        
        ";
    }
        if (isset($_POST['risolto'])) {
            $today = date("Y-m-d");
            $id_row_risolto_ok = $_POST['id_row_risolto'];
            $ris_aggiornata = $_POST['ris_aggiornata'];
            //$pos_sensore_aggiorno_errore = $_POST['pos_sensore'];

            mysqli_query($con, "UPDATE result SET ris='$ris_aggiornata',risolto_il='$today' WHERE id='$id_row_risolto_ok' ");

            $_SESSION['message'] = "Address updated!";

    }
    mysqli_close($con);
    ?>
    </div>
    </div>
<?php
include 'footer.php';
?>

