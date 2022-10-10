<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/blood_model.php');
$bloodGrp_object = new Blood_Grp($conn);
$blood_result = $bloodGrp_object->give_Allgroups();

$patient_id = isset($_REQUEST['patient_id']) ? base64_decode($_REQUEST['patient_id']) : '';

include('../model/patient_model.php');
$patient_object = new Patient($conn);
$result = $patient_object->get_SpecificPatient($patient_id);
$row = $result->fetch_assoc();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Patient's Details</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <style type="text/css">

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
            <div class="card-header text-center bg-dark">
              <i class="fa fa-user-injured fa-3x fa-pull-left" style="color:#FFF;"></i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>View Patient</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="update_patient" action="../controller/patient_controller.php?status=update_patient&patient_id=<?php echo $patient_id; ?>" method="post">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Patient's Full Name :</label>
                      <input id="name" name="name" value="<?php echo $row['patient_name']; ?>" type="text" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">N.I.C. No. :</label>
                      <input id="nic" name="nic" value="<?php echo $row['nic_no']; ?>" type="text" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Gender :</label><br>
                      <?php
                      if ($row['gender'] == "M") { ?>
                        <script>
                          $(document).ready(function(e) {
                            $('#male').prop('checked', true);
                          });
                        </script>
                      <?php } else { ?>
                        <script>
                          $(document).ready(function(e) {
                            $('#female').prop('checked', true);
                          });
                        </script>
                      <?php } ?>
                      <span style="color:#000;">Male :</span> <input id="male" value="M" name="gender" type="radio"> &nbsp;
                      <span style="color:#000;">Female :</span> <input id="female" value="F" name="gender" type="radio">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Age :</label>
                      <input id="age" name="age" type="number" value="<?php echo $row['age']; ?>" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Blood Group :</label>
                      <select name="blood_grp" id="blood_grp" class="form-control" disabled>
                        <option value="<?php echo $row['blood_grp'] ?>"><?php echo $row['grp_name']; ?></option>
                        <?php while ($blood_row = $blood_result->fetch_assoc()) {
                          if ($row['blood_grp'] == $blood_row['grp_id']) {
                            continue;
                          }
                        ?>
                          <option value="<?php echo $blood_row['grp_id']; ?>"><?php echo $blood_row['grp_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>

                <button id="make_chng" class="btn btn-warning"><b><span class="fa fa-edit"></span> Make Changes</b></button>
                <button id="submit" type="submit" class="btn btn-success" disabled><b><span class="fa fa-check"></span> Save Changes</b></button>

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


  <script src="../js/validations/UpdatePatient_val.js"></script>
</body>

</html>