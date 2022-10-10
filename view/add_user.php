<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add User</title>

  <style type="text/css">
    #pw_addon,
    #cpw_addon,
    input[type=file] {
      cursor: pointer;
    }

    #resno {
      display: inline;
    }
  </style>

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
              <i class="fa fa-user-plus fa-3x fa-pull-left text-white"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>Add New User</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="signup" action="../controller/user_controller.php?status=add_user" method="post" enctype="multipart/form-data">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">First Name :</label>
                      <input id="fname" name="fname" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Last Name :</label>
                      <input id="lname" name="lname" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">N.I.C. No. :</label>
                      <input id="nic" name="nic" type="text" class="form-control" autocomplete="off" required>
                      <h6 id="user_res" style="color:#F00;"></h6>
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
                      <label class="alert-dark">Residential No. :(Optional)</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="resno" name="resno" type="number" class="form-control" autocomplete="off" maxlength="9">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Mobile No :(Required)</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">07</span>
                        </span>
                        <input id="mno" name="mno" type="number" class="form-control" autocomplete="off" required maxlength="8">
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Gender :</label><br>
                      <span style="color:#000;">Male :</span> <input name="gender" type="radio" value="M"> &nbsp;
                      <span style="color:#000;">Female :</span> <input name="gender" type="radio" value="F">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">City :</label>
                      <input name="city" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Email Address :</label>
                      <input id="email" name="email" type="email" class="form-control" placeholder="example@gmail.com" autocomplete="off" required>
                      <h4 id="email_res" style="color:#F00; font-size:18px;"></h4>
                      <p id="email_help" class="help-block">(Please enter working Email Address)</p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">User Name :</label>
                      <input id="uname" name="uname" type="text" class="form-control" autocomplete="off" required>
                      <h4 id="uname_res" style="color:#F00; font-size:18px;"></h4>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Password :</label>
                      <div class="input-group">
                        <input id="pw" name="pw" type="password" class="form-control" required>
                        <span class="input-group-append">
                          <span id="pw_addon" class="input-group-text">
                            <i id="pw_icon" class="fa fa-eye"></i>
                          </span>
                        </span>
                      </div>
                      <div id="pws_div" class="progress hidden" style="width:90%;">
                        <div id="pws_progress" class="progress-bar">
                          <span id="pws_res"></span>
                        </div>
                      </div>
                      <p class="help-block">Password must have <b>at least 6 characters</b></p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Confirm Password :</label>
                      <div class="input-group">
                        <input id="cpw" type="password" class="form-control" required>
                        <span class="input-group-append">
                          <span id="cpw_addon" class="input-group-text">
                            <i id="cpw_icon" class="fa fa-eye"></i>
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>


                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">User Role :</label>
                      <select name="role" id="role" class="form-control" required>
                        <option value="">---</option>
                        <?php
                        include_once('../model/role_model.php');
                        $role_object = new Role($conn);
                        $get_roles = $role_object->getRoles();
                        while ($row = $get_roles->fetch_assoc()) {
                        ?>
                          <option value="<?php echo $row['role_id']; ?>"><?php echo $row['role_name']; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">User Image :</label>
                      <input id="uimg" name="uimg" type="file" class="form-control-file" onChange="readURL(this)">
                      <div>
                        <img id="prev_img">
                      </div>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-info"><b><span class="fa fa-save"></span> Register</b></button>

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


  <script src="../js/validations/addUser_val.js"></script>
</body>

</html>