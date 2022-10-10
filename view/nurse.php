<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/staff_model.php');
$nurse_object = new Nurse($conn);
$result = $nurse_object->give_AllNurse();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Nurse</title>

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

      <div class="row pl-3">
        <button class="btn btn-dark" id="add_nusreBtn">Add New Nurse <i class="fa fa-plus-circle"></i></button>
      </div>

      <!--Row Start-->
      <div class="row mt-2" id="fRow">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-user-nurse fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Add Nurse</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="addNurse_Form" action="../controller/staff_controller.php?status=add_nurse" method="post">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">First Name :</label>
                      <input id="fname" name="fname" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Last Name :</label>
                      <input id="lname" name="lname" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">N.I.C. No. :</label>
                      <input id="nic" name="nic" type="text" class="form-control" autocomplete="off" required>
                      <h5 style="color:#F00;" id="nurse_res"></h5>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Contact No.</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="con_no" name="con_no" type="number" class="form-control" autocomplete="off" required>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Gender :</label><br>
                      <span style="color:#000;">Male :</span> <input value="M" name="gender" type="radio"> &nbsp;
                      <span style="color:#000;">Female :</span> <input value="F" name="gender" type="radio">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Email :</label>
                      <input id="email" name="email" value="" type="email" class="form-control" autocomplete="off" placeholder="(optional)">
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Qualifications/Certificates :</label>
                      <textarea class="form-control" name="qualif" required></textarea>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-plus"></span> Add</b></button>

              </form>
              <!--Reg Form End-->
            </div>
          </div>

        </div>

      </div>
      <!--1st Row End-->

      <hr>

      <!--2nd Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-user-tie fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Nurse</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="mng_nurseTB" style="width:100%;">
                <thead>
                  <tr class="tbhead">
                    <td>#Nurse ID</td>
                    <td>Name</td>
                    <td></td>
                    <td>Status</td>
                    <td>Action</td>
                  </tr>
                </thead>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['nurse_id']; ?></td>
                    <td><?php echo "Mrs. " . $row['fname'] . " " . $row['lname']; ?></td>
                    <td>
                      <a href="../report/DisplayNurse_report.php?nurse_id=<?php echo base64_encode($row['nurse_id']); ?>" class="btn btn-danger btn-sm"><u><i class="fa fa-file-pdf"></i> View Report</u></a>
                    </td>
                    <td><?php if ($row['nurse_status'] == 1) {
                          $status = "<span style='color:#090;'>Active</span>";
                        } else {
                          $status = "<span style='color:#F00;'>Blocked</span>";
                        }
                        echo $status; ?>
                    </td>
                    <td><a href="display_nurse.php?nurse_id=<?php echo base64_encode($row['nurse_id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> View/Edit</a> &nbsp;
                      <?php if ($row['nurse_status'] == 1) { ?>
                        <a href="../controller/staff_controller.php?status=active_blockNurse&nurse_id=<?php echo $row['nurse_id']; ?>&nurse_status=<?php echo $row['nurse_status']; ?>" class="btn btn-warning btn-sm BlockConf"><i class="fa fa-user-lock"></i> Block</a>
                      <?php } else { ?>
                        <a href="../controller/staff_controller.php?status=active_blockNurse&nurse_id=<?php echo $row['nurse_id']; ?>&nurse_status=<?php echo $row['nurse_status']; ?>" class="btn btn-success btn-sm ActiveConf"><i class="fa fa-lock-open"></i> Activate</a>
                      <?php } ?> &nbsp;
                      <a href="../controller/staff_controller.php?status=removeNurse&nurse_id=<?php echo $row['nurse_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>

              <a href="../report/ListedNurse_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download Report</a>
            </div>
          </div>
        </div>

      </div>

    </div>
    <!--2nd Row End-->

    <div>&nbsp;</div>

  </div>

  <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/nurse_val.js"></script>
</body>

</html>