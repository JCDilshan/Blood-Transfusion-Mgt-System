<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/schedule_model.php');
$appoint_obj = new Appointment($conn);
$result = $appoint_obj->give_ListedAppointments();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Listed Appointment</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>

  <style type="text/css">
    .tbhead {
      background-color: #666;
      color: #FFF;
    }
  </style>

</head>

<body>
  <!-- ///////////////////////// Content wrapper start ///////////////////////// -->
  <div class="wrapper d-flex align-items-stretch">

    <?php
    include('../includes/nav_bar.php');
    ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-clipboard-list fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Appointments</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="mng_appTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Appointment ID</td>
                    <td>#Schedule ID</td>
                    <td>Appointment Date/Time</td>
                    <td>Donor N.I.C.</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['app_id']; ?></td>
                    <td><?php echo $row['schedule_id']; ?></td>
                    <td><?php echo date("j M Y g:i A", strtotime($row['set_date'] . " " . $row['start_time'])); ?></td>
                    <td><?php echo $row['donor_nic']; ?></td>
                    <td>
                      <a href="../controller/schedule_controller.php?status=cancel_appoint&app_id=<?php echo $row['app_id']; ?>&schedule_id=<?php echo $row['schedule_id']; ?>&donor_nic=<?php echo $row['donor_nic']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times"></i> Cancel</a>
                    </td>
                  </tr>
                <?php } ?>

              </table>
            </div>
          </div>

        </div>
      </div>
      <div>&nbsp;</div>
    </div>
    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>

  <script>
    $(document).ready(function(e) {
      ///////////////////////////// Enable data tables plugin ///////////////////////////
      $('#mng_appTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#mng_appTB').on('click', '.RemoveConf', function() {
        var loc = $(this).attr('href');
        swal({
          title: "Are you sure You Want To Cancel This Appointment ?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Cancel",
          cancelButtonClass: "btn-info",
          closeOnConfirm: true
        }, function(isConfirm) {
          if (isConfirm === true) {
            window.location.href = loc;
          }
        });
        return false;
      });
    });
  </script>
</body>

</html>