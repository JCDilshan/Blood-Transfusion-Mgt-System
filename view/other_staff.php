<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/staff_model.php');
$other_staff_obj = new Other_Staff($conn);
$result = $other_staff_obj->give_AllMembers();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Other Staff</title>

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
        <button id="add_MemBtn" class="btn btn-dark">Add New Member <i class="fa fa-plus-circle"></i></button>
      </div>

      <!--Row Start-->
      <div id="fRow" class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark text-white">
              <i class="fa fa-users fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Add Staff Member</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="addMem_Form" action="../controller/staff_controller.php?status=add_member" method="post">

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
                      <h5 style="color:#F00;" id="mem_res"></h5>
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
              <i class="fa fa-user-tie fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Helping Staff</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="mng_memTB" style="width:100%;">
                <thead>
                  <tr class="tbhead">
                    <td>#Member ID</td>
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
                    <td><?php echo $row['mem_id']; ?></td>
                    <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                    <td>
                      <a href="../report/DisplayMember_report.php?mem_id=<?php echo base64_encode($row['mem_id']); ?>" class="btn btn-danger btn-sm"><u><i class="fa fa-file-pdf"></i> View Report</u></a>
                    </td>
                    <td><?php if ($row['mem_status'] == 1) {
                          $status = "<span style='color:#090;'>Active</span>";
                        } else {
                          $status = "<span style='color:#F00;'>Blocked</span>";
                        }
                        echo $status; ?>
                    </td>
                    <td><a href="display_otherStaff.php?mem_id=<?php echo base64_encode($row['mem_id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> View/Edit</a> &nbsp;
                      <?php if ($row['mem_status'] == 1) { ?>
                        <a href="../controller/staff_controller.php?status=active_blockMem&mem_id=<?php echo $row['mem_id']; ?>&mem_status=<?php echo $row['mem_status']; ?>" class="btn btn-warning btn-sm BlockConf"><i class="fa fa-user-lock"></i> Block</a>
                      <?php } else { ?>
                        <a href="../controller/staff_controller.php?status=active_blockMem&mem_id=<?php echo $row['mem_id']; ?>&mem_status=<?php echo $row['mem_status']; ?>" class="btn btn-success btn-sm"><i class="fa fa-lock-open"></i> Activate</a>
                      <?php } ?> &nbsp;
                      <a href="../controller/staff_controller.php?status=removeMem&mem_id=<?php echo $row['mem_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>

              <a href="../report/ListedOtherStaff_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download Report</a>
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


  <script src="../js/validations/other_staff_val.js"></script>
</body>

</html>