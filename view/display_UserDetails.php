<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$user_id = isset($_REQUEST['user_id']) ? base64_decode($_REQUEST['user_id']) : '';

include_once('../model/user_model.php');
$user_object = new User($conn);
$result = $user_object->give_info($user_id);
$row    = $result->fetch_assoc();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Display User</title>

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

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <i class="fa fa-user-edit fa-3x fa-pull-left" style="color:#FFF;"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>View User</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="edit_user" action="../controller/user_controller.php?status=save_changeUser&user_id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">

                <input id="curr_role" type="hidden" value="<?php echo $_SESSION["user_role"]; ?>">
                <input id="user_id" type="hidden" value="<?php echo $user_id; ?>">

                <h4>Profile Status - <?php $status = $row['user_status'];
                                      if ($status == '1') {
                                        $status = "<span style='color:#0F0;'>Active</span>";
                                      } else if ($status == '0') {
                                        $status = "<span style='color:#F00;'>Blocked</span>";
                                      }
                                      echo $status; ?>
                </h4>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">First Name :</label>
                      <input id="fname" name="fname" type="text" value="<?php echo $row['fname'] ?>" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Last Name :</label>
                      <input id="lname" name="lname" type="text" value="<?php echo $row['lname'] ?>" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">N.I.C. No. :</label>
                      <input id="nic" name="nic" type="text" value="<?php echo $row['nic_no'] ?>" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">D.O.B. :</label>
                      <input id="dob" name="dob" type="date" value="<?php echo $row['dob'] ?>" class="form-control" autocomplete="off" required readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Residential No. :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="resno" name="resno" type="number" class="form-control" value="<?php echo substr($row['res_no'], 1); ?>" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Mobile No :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">07</span>
                        </span>
                        <input id="mno" name="mno" type="number" class="form-control" value="<?php echo substr($row['mno'], 2); ?>" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Gender :</label><br>
                      <?php if ($row['gender'] == "M") { ?>
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

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">City :</label>
                      <input type="text" name="city" value="<?php echo $row['city'] ?>" class="form-control" autocomplete="off" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">Email Address :</label>
                      <input id="email" name="email" type="email" value="<?php echo $row['email'] ?>" class="form-control" autocomplete="off" readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <?php if ($_SESSION["user_role"] != 100) { ?>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="alert-dark">User Role :</label>
                        <select name="role" id="role" class="form-control" disabled>
                          <?php
                          include_once('../model/role_model.php');
                          $role_object = new Role($conn);
                          $result_roles = $role_object->getRoles();
                          $result_SpRole = $role_object->get_specificRole($row['user_role']);
                          $row_SpRole = $result_SpRole->fetch_assoc();
                          ?>
                          <option value="<?php echo $row['user_role'] ?>"><?php echo $row_SpRole['role_name']; ?></option>
                          <?php while ($row_roles = $result_roles->fetch_assoc()) {
                            if ($row['user_role'] == $row_roles['role_id'] || $row_roles['role_id'] == 100) {
                              continue;
                            }
                          ?>
                            <option value="<?php echo $row_roles['role_id']; ?>"><?php echo $row_roles['role_name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-dark">User Image :</label>
                      <input type="hidden" name="defUimg" value="<?php echo $row['user_img']; ?>">
                      <input id="uimg" name="uimg" type="file" accept="image/*" class="form-control" disabled onChange="readURL(this)">
                    </div>
                    <div>
                      <img id="prev_img" src="../images/users/<?php echo $row['user_img']; ?>" width="100" height="100">
                    </div>
                  </div>

                </div>

                <br>

                <button id="make_chng" class="btn btn-warning"><b><span class="fa fa-edit"></span> Edit</b></button>
                <button id="submit" type="submit" class="btn btn-success" disabled hidden><b><span class="fa fa-save"></span> Save</b></button>

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


  <script src="../js/validations/editUser_val.js"></script>

</body>

</html>