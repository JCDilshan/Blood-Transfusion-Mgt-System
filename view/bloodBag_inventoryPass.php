<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 5) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/blood_model.php');
$comp_object = new Blood_Component($conn);
$result = $comp_object->give_Allcomponents();

include('../model/camp_model.php');
$camp_obj = new Camp($conn);
$result_2 = $camp_obj->getAllHeldcamps();

include('../model/schedule_model.php');
$hospital_obj = new Hospital($conn);
$result_3 = $hospital_obj->get_AllHospitals();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Inventory Pass</title>

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

      <!--1st Row Start-->
      <div class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <span style="font-size:28px; letter-spacing:5px; color:#FFF;"><b>Pass To Inventory</b></span>
            </div>
            <div class="card-body">
              <form id="InvenPass" action="../controller/donation_controller.php?status=inventory_pass" method="post">

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Donor ID :</label>
                      <input id="donor_id" name="donor_id" type="number" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Donated Venue :</label><br>
                      <span style="color:#000;">By Hospital</span>&nbsp;<input value="hosp" type="radio" name="where"> &nbsp;&nbsp;
                      <span style="color:#000;">By Blood Camp</span>&nbsp;<input value="camp" type="radio" name="where">
                    </div>
                  </div>

                  <div class="col-md-4" id="Bycamp">
                    <div class="form-group">
                      <label class="alert-dark">Camp ID :</label>
                      <select id="camp_id" name="camp_id" class="form-control">
                        <option value="0">Select Camp ID</option>
                        <?php while ($row_2 = $result_2->fetch_assoc()) { ?>
                          <option value="<?php echo $row_2['camp_id']; ?>"><?php echo $row_2['camp_id']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4" id="Byhosp">
                    <div class="form-group">
                      <label class="alert-dark">Hospital :</label>
                      <select id="hosp_id" name="hosp_id" class="form-control">
                        <option value="0">Select Hospital ID</option>
                        <?php while ($row_3 = $result_3->fetch_assoc()) { ?>
                          <option value="<?php echo $row_3['hospital_id']; ?>"><?php echo $row_3['hospital_id']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Blood Bag ID :</label>
                      <input id="bag_id" name="bag_id" type="text" class="form-control" autocomplete="off" required>
                      <p id="attention" style="color:#F00; font-weight:bold;">(ATTENTION: Please Enter Blood Bag ID Very Carefully.)</p>
                      <h4 id="bag_res" style="color:#F00;"></h4>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Blood Group :</label>
                      <input id="blood_grp" type="text" class="form-control" readonly>
                      <input id="grp_id" type="hidden" name="blood_grp">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Component Type :</label>
                      <select id="comp_type" name="comp_type" class="form-control" required>
                        <option value="">---</option>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                          <option value="<?php echo $row['comp_id']; ?>"><?php echo $row['comp_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Bag Sealed Date :</label>
                      <input id="sealed_date" name="sealed_date" type="date" class="form-control" required>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-share"></span> Pass</b></button>

              </form>
            </div>
          </div>

        </div>

      </div>
      <!--1st Row End-->

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/inventoryPass_val.js"></script>
</body>

</html>