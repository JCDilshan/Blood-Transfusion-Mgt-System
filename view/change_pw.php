<?php
include('../includes/session&redirect.php');

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Password Reset</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>
  <style type="text/css">
    #pw_addon,
    #cpw_addon,
    input[type=file] {
      cursor: pointer;
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

        <div class="col-md-8 offset-2">

          <!--cardStart-->
          <div class="card">
            <div class="card-header text-center bg-dark">
              <h4 style="color:#FFF;"> <i class="fa fa-lock-open"></i> Reset Password</h4>
            </div>

            <br />

            <div class="card-body">
              <!--Form Start-->
              <form id="pw_change" action="../controller/login_controller.php?status=change_pw" method="post">

                <div class="form-group">
                  <label class="alert-dark">Enter Current Password :</label>
                  <div class="input-group">
                    <span class="input-group-append">
                      <span class="input-group-text"> <i class="fa fa-question"></i> </span>
                    </span>
                    <input name="cur_pw" type="password" class="form-control" autocomplete="off" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="alert-dark">New Password :</label>
                  <div class="input-group">
                    <input id="new_pw" name="new_pw" type="password" class="form-control" required>
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

                <button type="submit" class="btn btn-warning"><b> <i class="fa fa-redo"></i> Change</b></button>
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


  <script src="../js/validations/changePW_val.js"></script>
</body>

</html>