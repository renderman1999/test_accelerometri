<?php
include('login.php'); // Include Login Script
if ((isset($_SESSION['username']) != ''))
{
    header('Location: home.php');
}

?>

<html lang="it">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js
"></script>
    <script type='text/javascript' src='http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js'></script>

    <link rel="stylesheet" type="text/css" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />

    <style>


</style>
    <script>$(document).ready(function () {
            $.noConflict();
            var table = $('#myTable').DataTable(

                {paging: true,
                    "pagingType": "numbers",
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Italian.json"
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', {extend: 'pdf',text: 'PDF',className: 'btn btn-success'}, {extend: 'print',text: 'Stampa',className: 'btn btn-success'}
                    ]
                },

            );
        });
        $(".dataTables_filter input").addClass("form-control");



    </script>


    <script src="include/md.js"></script>
    <link rel="stylesheet" href="include/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="include/md.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>


    <?php
    //connesisone al db
    $con=mysqli_connect("localhost","root","","test_accelerometri");
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $tot_stazioni_lunitek = mysqli_query($con,"SELECT count(DISTINCT Sigla)as tot_stazioni_lunitek from anagrafica WHERE Gestore LIKE 'lunitek';
");

    ?>


</head>

<body style="font-family: Roboto, sans-serif !important;" >

<header>
    <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 ">
                    <h4 class="text-white">Test Accelerometri</h4>
                    <p class="text-muted">

                        TOT.LUNITEK: <?php
                        while($row = mysqli_fetch_array($tot_stazioni_lunitek)) {
                            echo $row['tot_stazioni_lunitek'];}
                        ?>


                    </p>
                </div>
                <div class="col-sm-4 ">
                    <h4 class="text-white">Menu</h4>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Vista generale</a></li>

                    </ul>
                </div>
<?php
if (stripos($_SERVER['REQUEST_URI'], 'single.php')){
    echo "
                <div class='col-sm-4'>
                    <h4 class='text-white'>Stazione Corrente</h4>
                    <ul class='list-unstyled'>
                        <button type='button' onclick='myFunction()' class='btn btn-success'/>Stampa vista</button>
<script>
function myFunction() {
    window.print();
}
</script>
";}
                        ?>
                    </ul>
                </div>


            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <!--<a href="#" class="navbar-brand d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                <strong>Album</strong>
            </a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>
