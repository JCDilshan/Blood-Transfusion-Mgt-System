<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/schedule_model.php');
$hosp_obj = new Hospital($conn);
$result = $hosp_obj->give_AllHospitals();

include('../model/camp_model.php');
$camp_object = new Camp($conn);
$result_2 = $camp_object->give_AllHeldcamps();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Donation</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

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
      <div class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-fill-drip fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Add New Blood Donation</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="add_donation" action="../controller/donation_controller.php?status=add_donation" method="post">

                <input type="hidden" name="donor_id" id="donor_id">

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Donor N.I.C. :</label>
                      <input id="nic" name="nic" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-8">
                    <div class="form-group">
                      <label class="alert-dark">Donor Name :</label>
                      <input id="name" type="text" class="form-control" autocomplete="off" readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Donor's Blood Group :</label>
                      <input id="blood_grp" name="blood_grp" type="text" class="form-control" autocomplete="off" readonly>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="alert-dark">Donated By :</label><br>
                      <span style="color:#000;"> Hospital</span>&nbsp;<input value="hospital" type="radio" name="where">&nbsp;&nbsp;
                      <span style="color:#000;">Blood Camp</span>&nbsp;<input value="camp" type="radio" name="where">
                    </div>
                  </div>

                  <div class="col-md-5" id="Byhosp">
                    <div class="form-group">
                      <label class="alert-dark">Hospital :</label>
                      <select id="hosp_id" name="hosp_id" class="form-control">
                        <option value="0">Select Hospital..</option>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                          <option value="<?php echo $row['hospital_id']; ?>"><?php echo $row['hospital_name'] . " - " . $row['location']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4" id="Bycamp">
                    <div class="form-group">
                      <label class="alert-dark">Camp ID :</label>
                      <select id="camp_id" name="camp_id" class="form-control">
                        <option value="0">Select Camp ID..</option>
                        <?php while ($row_2 = $result_2->fetch_assoc()) { ?>
                          <option value="<?php echo $row_2['camp_id']; ?>"><?php echo $row_2['camp_id']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Donated Date :</label>
                      <input type="date" id="don_date" name="don_date" class="form-control" autocomplete="off" readonly required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Bag ID :</label>
                      <input type="text" id="bag_id" name="bag_id" class="form-control" autocomplete="off" required>
                      <p id="attention" style="color:#F00; font-weight:bold;">(ATTENTION: Please Enter Blood Bag ID Very Carefully.)</p>
                      <h4 id="bag_res" style="color:#F00;"></h4>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-check"></span> Save</b></button>

              </form>
              <!--Reg Form End-->
            </div>
          </div>

        </div>

      </div>
      <!--1st Row End-->

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/donation_val.js"></script>
</body>

</html>