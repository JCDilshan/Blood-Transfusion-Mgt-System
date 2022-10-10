<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$schedule_id = isset($_REQUEST['sched_id']) ? base64_decode($_REQUEST['sched_id']) : '';

include('../model/schedule_model.php');
$schedule_obj = new Schedule($conn);
$hospital_obj = new Hospital($conn);

$result_2 = $schedule_obj->get_specificShedule($schedule_id);
$row_2 = $result_2->fetch_assoc();

$result = $hospital_obj->get_SpecificHospital($row_2['hospital_id']);
$row = $result->fetch_assoc();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Edit Schedule</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <style type="text/css">

  </style>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">

    <?php include('../includes/nav_bar.php'); ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-8 offset-2">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <i class="fa fa-edit fa-3x fa-pull-left" style="color:#FFF;"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>Edit Schedule</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form action="../controller/schedule_controller.php?status=update_schedule&sched_id=<?php echo $schedule_id; ?>" method="post">

                <!--<div>../controller/schedule_controller.php?status=update_schedule&sched_id=<?php //echo $schedule_id; 
                                                                                                ?></div>-->

                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-secondary">Location(Venue) :</label>
                      <input class="form-control" value="<?php echo $row['hospital_name'] . " - " . $row['location']; ?>" readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">Date :</label>
                      <input value="<?php echo $row_2['set_date']; ?>" class="form-control" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">Start Time :</label>
                      <input value="<?php echo $row_2['start_time']; ?>" class="form-control" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">End Time :</label>
                      <input value="<?php echo $row_2['end_time']; ?>" type="end_time" class="form-control" readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">New Total Slots :</label>
                      <input id="slots" name="new_slots" type="number" value="<?php echo $row_2['total_slots']; ?>" class="form-control" required>
                      <input id="slots" name="old_slots" type="hidden" value="<?php echo $row_2['total_slots']; ?>" class="form-control">
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><i class="fa fa-check"></i> Update</b></button>

              </form>
              <!--Reg Form End-->
            </div>
          </div>

        </div>

      </div>
      <!--Row End-->

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>

</body>

</html>