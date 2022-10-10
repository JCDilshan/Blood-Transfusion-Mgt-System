<?php
include('../includes/session&redirect.php');

include_once('../model/role_model.php');
$role_object  = new Role($conn);
$result_roles = $role_object->getRoles();

include('../model/user_model.php');

$user_id = $_SESSION['user_id'];

$user_object = new User($conn);
$result_user = $user_object->give_info($user_id);
$row_user    = $result_user->fetch_assoc();

$result_SpRole = $role_object->get_specificRole($row_user['user_role']);
$row_SpRole = $result_SpRole->fetch_assoc();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Self Info</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <style type="text/css">
    .help-block u {
      color: #FF8C8C;
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

          <!--cardStart-->
          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-user fa-3x fa-pull-left"> </i> <i class="fa fa-info-circle fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Your Info</b></span>
            </div>

            <br />

            <div class="card-body">
              <!--Form Start-->
              <form id="selfInfo" action="../controller/user_controller.php?status=save_changeInfo" method="post" enctype="multipart/form-data">

                <input type="hidden" id="role" value="<?php echo $_SESSION["user_role"]; ?>">

                <h4>Profile Status - <?php $status = $row_user['user_status'];
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
                      <label class="alert-dark">First Name :</label>
                      <input id="fname" name="fname" type="text" class="form-control" value="<?php echo $row_user['fname']; ?>" autocomplete="off" readonly required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Last Name :</label>
                      <input id="lname" name="lname" type="text" class="form-control" value="<?php echo $row_user['lname']; ?>" autocomplete="off" readonly required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">N.I.C. Number :</label>
                      <input id="nic" name="nic" type="text" class="form-control" value="<?php echo $row_user['nic_no']; ?>" autocomplete="off" readonly required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Residential No. :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="resno" name="resno" type="number" class="form-control" value="<?php echo substr($row_user['res_no'], 1); ?>" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Mobile No :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">07</span>
                        </span>
                        <input id="mno" name="mno" type="number" class="form-control" value="<?php echo substr($row_user['mno'], 2); ?>" autocomplete="off" readonly required>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">City :</label>
                      <input name="city" type="text" class="form-control" value="<?php echo $row_user['city']; ?>" readonly required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Email Address :</label>
                      <input name="email" id="email" type="email" class="form-control" value="<?php echo $row_user['email']; ?>" autocomplete="off" readonly required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Gender :</label><br>
                      <?php if ($row_user['gender'] == "M") { ?>
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
                      <span style="color:#000;">Male :</span> <input value="M" id="male" name="gender" type="radio"> &nbsp;
                      <span style="color:#000;">Female :</span> <input value="F" id="female" name="gender" type="radio">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">D.O.B. :</label>
                      <input id="dob" name="dob" type="date" value="<?php echo $row_user['dob'] ?>" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">User Image :</label>
                      <input type="hidden" name="defUimg" value="<?php echo $row_user['user_img']; ?>">
                      <input onChange="readURL(this)" id="uimg" name="uimg" type="file" accept="image/*" class="form-control-file" disabled>
                      <div>
                        <img id="prev_img" src="../images/users/<?php echo $row_user['user_img']; ?>" height="100" width="90">
                      </div>
                    </div>
                  </div>

                </div>
                <?php if ($_SESSION['user_role'] != 1) { ?>
                  <h5 class="text-dark">NOTE : You cannot change <u class="text-danger">NIC</u>, and <u class="text-danger">Email Address</u> without Administrator permission.</h5>
                <?php } ?>

                <br>

                <button id="make_chng" type="button" class="btn btn-warning"><b> <i class="fa fa-edit"></i> Make Changes</b></button>
                <button id="submit" type="submit" class="btn btn-success" disabled hidden><i class="fa fa-download"></i> Save Changes</button>
              </form>
              <!--Form End-->
            </div>
          </div>
          <!--cardEnd-->
        </div>
      </div>
      <!--Row End-->

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/selfInfo_val.js"></script>
</body>

</html>