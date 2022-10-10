<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/blood_model.php');
$bloodGrp_object = new Blood_Grp($conn);
$blood_result = $bloodGrp_object->give_Allgroups();

$donor_id = isset($_REQUEST['donor_id']) ? base64_decode($_REQUEST['donor_id']) : '';

include('../model/donor_model.php');
$donor_object = new Donor($conn);
$result = $donor_object->get_SpecificDonor($donor_id);
$row = $result->fetch_assoc();

$singleBlood_result = $bloodGrp_object->give_SpecificGroup($row["blood_grp"]);
$singleBlood_row = $singleBlood_result->fetch_assoc();

include('../model/Data_model.php');
$loc_object = new Location($conn);
$loc_result = $loc_object->get_allDistricts();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Donor's Details</title>

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
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-user-edit fa-3x fa-pull-left"></i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>View Donor</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="update_donor" action="../controller/donor_controller.php?status=update_donor&donor_id=<?php echo $donor_id; ?>" method="post">

                <h4>Donor ID : <?php echo $row['donor_id']; ?></h4>
                <h4>Profile Status - <?php $status = $row['donor_status'];
                                      if ($status == '1') {
                                        $status = "<span style='color:#0F0;'>Active</span>";
                                      } else if ($status == '0') {
                                        $status = "<span style='color:#F00;'>Blocked</span>";
                                      }
                                      echo $status; ?>
                </h4>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Donor's Full Name :</label>
                      <input id="name" name="name" value="<?php echo $row['donor_name']; ?>" type="text" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">N.I.C. No. :</label>
                      <input id="nic" name="nic" value="<?php echo $row['donor_nic']; ?>" type="text" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Gender :</label><br>
                      <?php
                      if ($row['donor_gender'] == "M") { ?>
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

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">D.O.B. :</label>
                      <input id="dob" name="dob" value="<?php echo $row['donor_dob']; ?>" type="date" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Contact No. :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="con_no" name="con_no" value="<?php echo substr($row['donor_contact'], 1); ?>" type="text" class="form-control" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Email Address :</label>
                      <input id="email" name="email" value="<?php echo $row['donor_email']; ?>" type="email" class="form-control" placeholder="(Optional)" autocomplete="off" readonly>
                      <div id="email_res" style="color:#F00; font-size:18px;">
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">District :</label>
                      <select id="disc_id" class="form-control" name="disc_id" required disabled>
                        <?php $loc_result2 = $loc_object->get_SpecificDisct($row['district_id']);
                        $loc_row2 = $loc_result2->fetch_assoc(); ?>
                        <option value="<?php echo $loc_row2['district_id']; ?>"><?php echo $loc_row2['district_name']; ?></option>
                        <?php while ($loc_row = $loc_result->fetch_assoc()) {
                          if ($row['district_id'] == $loc_row['district_id']) {
                            continue;
                          };
                        ?>
                          <option value="<?php echo $loc_row['district_id']; ?>"><?php echo $loc_row['district_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Blood Group :</label>
                      <select name="blood_grp" id="blood_grp" class="form-control" disabled>
                        <option value="<?php echo $row['blood_grp'] ?>"><?php echo $singleBlood_row["grp_name"]; ?></option>
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

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Other Info. :</label>
                      <textarea id="other_info" name="other_info" class="form-control" readonly><?php echo $row['donor_otherInfo']; ?></textarea>
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


  <script src="../js/validations/UpdateDonor_val.js"></script>
</body>

</html>