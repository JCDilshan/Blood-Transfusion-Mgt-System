<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/schedule_model.php');
$hosp_obj = new Hospital($conn);
$result_hosp = $hosp_obj->give_AllHospitals();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Make Appointment</title>

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
      <!--Content Start-->

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-8 offset-2">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-calendar-alt fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Make Appointment</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="make_appoin" action="../controller/schedule_controller.php?status=set_appointment" method="post">

                <div class="row">

                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="alert-secondary">Donor N.I.C. :</label>
                      <input name="donor_nic" id="nic" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-7">
                    <div class="form-group">
                      <label class="alert-secondary">Donor Name :</label>
                      <input id="donor_name" type="text" class="form-control" readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-secondary">Set Nearest Location(Venue) :</label>
                      <select name="venue" id="loc" class="form-control" disabled required>
                        <option value="">---</option>
                        <?php while ($row_hosp = $result_hosp->fetch_assoc()) { ?>
                          <option value="<?php echo $row_hosp['hospital_id']; ?>"><?php echo $row_hosp['hospital_name'] . " - " . $row_hosp['location']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-secondary">Preffered Date :</label>
                      <select name="pref_date" id="date" class="form-control" disabled required>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-secondary">Preffered Time :</label>
                      <select name="pref_time" id="time" class="form-control" disabled required>
                      </select>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-calendar-check"></span> Schedule Appointment</b></button>

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


  <script src="../js/validations/makeAppointment_val.js"></script>
</body>

</html>