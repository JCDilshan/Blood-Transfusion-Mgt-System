<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/staff_model.php');
$member_object = new Other_Staff($conn);

$mem_id = isset($_REQUEST['mem_id']) ? base64_decode($_REQUEST['mem_id']) : '';

$result = $member_object->get_SpecificMember($mem_id);
$row = $result->fetch_assoc();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>View Member</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>
  <style type="text/css">
    .tbhead {
      background-color: #666;
      color: #FFF;
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
      <div id="fRow" class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <i class="fa fa-user-edit fa-3x fa-pull-left" style="color:#FFF;"></i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>Update Member Details</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="editMemForm" action="../controller/staff_controller.php?status=update_member&mem_id=<?php echo $mem_id; ?>" method="post">

                <h4>Profile Status - <?php $status = $row['mem_status'];
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
                      <label class="alert-secondary">First Name :</label>
                      <input id="fname" name="fname" value="<?php echo $row['fname']; ?>" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Last Name :</label>
                      <input id="lname" name="lname" value="<?php echo $row['lname']; ?>" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">N.I.C. No. :</label>
                      <input id="nic" name="nic" value="<?php echo $row['nic_no']; ?>" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Contact No. :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="con_no" name="con_no" value="<?php echo substr($row['contact_no'], 1); ?>" type="number" class="form-control" autocomplete="off" required>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Gender :</label><br>
                      <?php
                      if ($row['gender'] == "M") { ?>
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
                      <label class="alert-secondary">Email :</label>
                      <input id="email" name="email" value="<?php echo $row['email']; ?>" type="text" class="form-control" autocomplete="off">
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Qualifications/Certificates :</label>
                      <textarea class="form-control" name="qualif" required><?php echo $row['qualif']; ?></textarea>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b> Save Changes</b></button>

              </form>
              <!--Reg Form End-->
            </div>
          </div>

        </div>
      </div>
      <!--1st Row End-->

    </div>

  </div>
  </div>



  <script src="../js/displayStaff.js"></script>
</body>

</html>