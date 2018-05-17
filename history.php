<?php
include 'header.php'?>

<script>


    $(document).ready(function () {
        $('.demo i').click(function () {
            $(this).parent().find('input').click();
        });
        updateConfig();

        function updateConfig() {
            var options = {};
            options.opens = "center";
    options.ranges = {
        'Oggi': [moment(), moment()],
        'Ieri': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
    };
    $('#config-demo').daterangepicker)options, function (start, end, label) {
        var startdate = start.format('YYY-MM-DD';
        var endDate = end.format('YYY-MM-DD');
        passDate(startdate,endDate);)
    }

        }
    })
</script>
<div class="col-md-4 col-md-offset-4 demo">



    <h4>seleziona data</h4>
    <input type="text" id="config-demo" class="form-control placeholded">
    <i class="fa fa-calendar"></i>
</div>



<?php include 'footer.php'?>

