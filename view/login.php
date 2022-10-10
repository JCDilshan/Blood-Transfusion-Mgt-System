<?php
$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
$pw_reset = isset($_GET['resstatus']) ? $_GET['resstatus'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <link rel="stylesheet" href="../css/login.css">

</head>

<?php
if ($pw_reset == 'ok') {
?>

  <script>
    $(document).ready(function(e) {
      swal("Well Done !", "Now You Can Login With Your New Password", "success");
    });
  </script>
<?php
} else if ($res_status == '0') {
?>

  <script>
    $(document).ready(function(e) {
      swal("<?php echo $msg; ?>", "Login Fail !", "error");
    });
  </script>
<?php
}
?>

<body style="background: linear-gradient(to right, gray, white);">

  <div class="container-fluid">

    <div class="row">

      <div class="col-md-3 p-0">

        <div id="side_col">

          <h1>WELCOME !</h1>

          <img src="../images/blood-image(mod).jpg" class="img-fluid" style="max-height: 280px;">
          <div class="side_content">

            <h3>BLOOD <br>TRANSFUSION MANAGEMENT SYSTEM</h3>
            <h4 class="mt-4" style="color: silver;">Every Drop Will Help !</h4>
          </div>

        </div>

      </div>


      <div class="col-md-9">

        <div>&nbsp;</div>

        <div class="row login-row pl-4">

          <div class="col-md-5">
            <!--Login Form Start-->
            <form action="../controller/login_controller.php?status=login" method="post">

              <div class="form-group">
                <label>
                  <h6>Username or Email:</h6>
                </label>
                <div class="input-group">
                  <input name="uname" type="text" class="form-control" autocomplete="off" required="required" placeholder="User Name...">
                </div>
              </div>

              <div class="form-group">
                <label>
                  <h6>Password :</h6>
                </label>
                <div class="input-group">
                  <!--<span class="input-group-prepend">
     <span class="input-group-text">
      <i class="fa fa-lock"></i>
     </span>
  </span>-->
                  <input id="pw" name="password" type="password" class="form-control" required="required" placeholder="Password...">

                  <span class="input-group-append">
                    <span id="pw_addon" class="btn input-group-text">
                      <i id="pw_icon" class="fa fa-eye"></i>
                    </span>
                  </span>

                </div>
              </div>

              <div>
                <a href="forgot_pw.php" class="fa-pull-right text-info fpw" style="padding-top:8px;">
                  <strong>Forgot Password ??</strong>
                </a>
              </div>

              <button type="submit" class="btn btn-dark"><b><span class="fa fa-sign-in-alt"></span> Login</b></button>

            </form>
            <!--Login Form End-->

          </div>


        </div>

      </div>

    </div>
    <script>
      $(document).ready(function(e) {

        ///////////////// Password fields visibility toggle //////////////////////
        $('#pw_addon').click(function(event) {
          var pw = $('#pw').val();
          if (pw == "" || pw == null) {
            swal("Field Empty", "", "warning");
            return false;
          } else {
            if ($('#pw').attr("type") == "password") {
              $('#pw').attr("type", "text");
            } else if ($('#pw').attr("type") == "text") {
              $('#pw').attr("type", "password");
            }
            $('#pw_icon').toggleClass('fa fa-eye fa fa-eye-slash');
          }
        });

      });
    </script>
</body>

</html>