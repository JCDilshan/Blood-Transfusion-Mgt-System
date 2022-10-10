<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/blood_model.php');
$bloodGrp_object = new Blood_Grp($conn);
$blood_result = $bloodGrp_object->give_Allgroups();

include('../model/Data_model.php');
$loc_object = new Location($conn);
$loc_result = $loc_object->give_allDistricts();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Donor Registration</title>

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
              <i class="fa fa-hand-holding-medical fa-3x float-left mr-1"></i><i class="fa fa-plus fa-3x fa-pull-left"></i> <span style="font-size:28px; letter-spacing:5px;"><b>Donor Registration</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="donor_reg" action="../controller/donor_controller.php?status=add_donor" method="post">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Donor's Full Name :</label>
                      <input id="name" name="name" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">N.I.C. No. :</label>
                      <input id="nic" name="nic" type="text" class="form-control" autocomplete="off" required>
                      <h5 id="donor_res" style="color:#F00;"></h5>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Gender :</label><br>
                      <span style="color:#000;">Male :</span> <input value="M" name="gender" type="radio"> &nbsp;
                      <span style="color:#000;">Female :</span> <input value="F" name="gender" type="radio">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">D.O.B. :</label>
                      <input id="dob" name="dob" type="date" class="form-control" autocomplete="off" required>
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
                        <input id="con_no" name="con_no" type="text" class="form-control" autocomplete="off" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Email Address :</label>
                      <input id="email" name="email" type="email" class="form-control" placeholder="(Optional)" autocomplete="off">
                      <div id="email_res" style="color:#F00; font-size:18px;">
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">District :</label>
                      <select class="form-control" name="disc_id" required>
                        <option value=""></option>
                        <?php while ($loc_row = $loc_result->fetch_assoc()) { ?>
                          <option value="<?php echo $loc_row['district_id']; ?>"><?php echo $loc_row['district_name']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Blood Group(Type) :</label>
                      <select id="blood_grp" name="blood_grp" class="form-control" required>
                        <option value="">---</option>
                        <?php while ($blood_row = $blood_result->fetch_assoc()) { ?>
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
                      <textarea name="otherInfo" class="form-control"></textarea>
                    </div>
                  </div>

                </div>


                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-share"></span> Register</b></button>

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


  <script src="../js/validations/donor_val.js"></script>
</body>

</html>