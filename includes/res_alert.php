<?php
if ($res_status == '1') {
?>
    <!--Registered Response Msg-->
    <div class="col-md-8 offset-2 res-alert">
        <div class="alert alert-success text-center">
            <h3 style="color:#090;"><b><?php echo $msg; ?></b></h3>
        </div>
    </div>
    <!---->
    <div>&nbsp;</div>
<?php
} else if ($res_status == '0') {
?>
    <!--Registered Response Msg-->
    <div class="col-md-8 offset-2 res-alert">
        <div class="alert alert-danger text-center">
            <h3 style="color:#C10000;"><b><?php echo $msg; ?></b></h3>
        </div>
    </div>
    <!---->
    <div>&nbsp;</div>
<?php
}
?>

<script>
    $(document).ready(function() {
        setTimeout(() => {
            $(".res-alert").fadeOut();
        }, 5000);
    });
</script>