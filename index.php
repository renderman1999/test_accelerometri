<?php
include 'header.php';

?>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php
//connesisone al db
$con=mysqli_connect("localhost","root","","test_accelerometri");
// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$stazioni_in_errore = mysqli_query($con,"SELECT DISTINCTROW( result.num_sensore),result.ris,result.nome_stazione, result.pos,result.data_file  FROM result,anagrafica
WHERE DATE (date_import) = CURDATE()
      AND ris = 'ERROR'
GROUP BY nome_stazione;
");


?>
<div class="container" style="margin-top: 100px">
    <h2 style="text-align: center">Accelerometri in Errore</h2>
<table  class="table table-striped table-bordered" id="myTable" style="height: auto">
    <thead>
    <tr>
        <th>SIGLA</th>
        <th>Numero Sensore</th>
        <th>RISULTATO</th>
        <th>Posizione</th>
        <th>In errore da</th>
    </tr>


    </thead>
<?php


while($row = mysqli_fetch_array($stazioni_in_errore)) {
    $oggi = date('d-m-y');

    $id_nome_stazione = $row['nome_stazione'];
$giorni_penale = 5;
//echo $oggi;
    $giorno = substr($row['data_file'],3,2);
    $mese = substr($row['data_file'],0,2);
    $anno = substr($row['data_file'],6,4);
    $trattino = '-';
    $data_file_pulita = $anno.$trattino.$mese.$trattino.$giorno;
    $start  = date_create($data_file_pulita);
    $end 	= date_create(); // Current time and date
    $diff  	= date_diff( $start, $end );
    $messaggio_penale = $diff->d - $giorni_penale;
    //echo "<br>giorno ",$giorno;
    //echo "<br>mese ",$mese;
    //echo "<br>anno:", $anno;

    //echo "<br>oggi:", $oggi;

  //  echo "<br>Data del file: ",$row['data_file'],"<br>";
//echo $data_file_pulita;
    //echo 'The difference is ';
    //echo  $diff->y . ' years, ';
    //echo  $diff->m . ' months, ';
    //echo  $diff->d . ' days, ';
    //echo  $diff->h . ' hours, ';
    //echo  $diff->i . ' minutes, ';
    //echo  $diff->s . ' seconds';
// Output: The difference is 28 years, 5 months, 19 days, 20 hours, 34 minutes, 36 seconds

    //echo 'The difference in days : ' . $diff->days;
// Output: The difference in days : 10398
    echo "<tr >";
echo  "<td ><a href='single.php?id=$row[nome_stazione]'> $row[nome_stazione]</a></td>";
echo  "<td>$row[num_sensore]</td>";
echo  "<td class='table-danger'>$row[ris]</td>";
echo  "<td>$row[pos]</td>";
if ($diff->d >= '5') {
    echo "<td style='color: red' data-toggle='tooltip' title='Penale applicabile da $messaggio_penale giorni'><i class='fa fa-clock-o'></i> $diff->d giorni e $diff->h ore</td>";
}
else {
    echo "<td style='color: green'><i class='fa fa-clock-o'></i> $diff->d giorni e $diff->h ore</td>";


}
    echo "</tr>";

//genero la tabella con i risultati della query
}

?>
</table>
</div>
<!--Table-->
<?php
mysqli_close($con);
?>
<?php
include 'footer.php';
?>
