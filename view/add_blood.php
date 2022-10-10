<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 3) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

if (isset($_REQUEST['bag_id'])) {
  $bag_id = base64_decode($_REQUEST['bag_id']);
} else {
  die("Sorry !! Cannot Access This Page...");
}

include('../model/donation_model.php');
$TestedDonations_obj = new Donation($conn);
$result = $TestedDonations_obj->get_SpecificPassedRecord($bag_id);
$row = $result->fetch_assoc();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Blood</title>

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
      <div class="row">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <i class="fa fa- fa-3x fa-pull-left"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>Add Blood</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="add_bloodForm" action="../controller/inventory_controller.php?status=add_blood" method="post">

                <div class="row">

                  <div class="col-sm-12">
                    <h3>Record Summary :</h3>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Donor ID :</label>
                      <input name="donor_id" value="<?php echo isset($row['donor_id']) ? $row['donor_id'] : ''; ?>" type="number" class="form-control" required readonly>
                    </div>
                  </div>

                  <?php if ($row['camp_id'] != NULL) { ?>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="alert-dark">Camp ID :</label>
                        <input name="camp_id" value="<?php echo isset($row['camp_id']) ? $row['camp_id'] : ''; ?>" type="number" class="form-control" required readonly>
                      </div>
                    </div>
                  <?php } else { ?>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="alert-dark">Hospital ID :</label>
                        <input name="hosp_id" value="<?php echo isset($row['hospital_id']) ? $row['hospital_id'] : ''; ?>" type="number" class="form-control" required readonly>
                      </div>
                    </div>
                  <?php } ?>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Blood Group :</label>
                      <input type="text" class="form-control" value="<?php echo $row['grp_name']; ?>" readonly>
                      <input type="hidden" name="blood_grp" value="<?php echo $row['grp_id']; ?>">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Component Type :</label>
                      <input type="text" class="form-control" value="<?php echo $row['comp_name']; ?>" readonly>
                      <input type="hidden" name="comp_type" value="<?php echo $row['comp_id']; ?>">
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Bag ID :</label>
                      <input name="bag_id" value="<?php echo $row['bag_id']; ?>" type="text" class="form-control" required readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Bag Sealed Date :</label>
                      <input name="sealed_date" value="<?php echo $row['sealed_date']; ?>" type="date" class="form-control" required readonly>
                      <h6 style="color:#FF2828;">(NOTE : Blood bag will automatically set as <b><u>expired bag</u></b> after <b><u>45 days</u></b> from the <b><u>Sealed date</u></b>)</h6>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-plus-square"></span> ADD</b></button>

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


  <script src="../js/validations/addBlood_val.js"></script>
</body>

</html>