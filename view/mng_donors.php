<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/donor_model.php');
$donor_object = new Donor($conn);
$result = $donor_object->give_Alldonors();

include_once('../model/blood_model.php');
$blood_object = new Blood_Grp($conn);

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Donors</title>

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
      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-hand-holding-medical fa-3x float-left mr-1"></i> <i class="fa fa-edit fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>View Listed Donors</b></span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center" style="width:100%;" id="mng_donorTB">
                <thead class="tbhead">
                  <tr>
                    <td>#Donor ID</td>
                    <td>Donor Name</td>
                    <td>N.I.C. No</td>
                    <td>Blood Group</td>
                    <td>Details</td>
                    <td>Status</td>
                    <td style="width:33%;">Action</td>
                  </tr>
                </thead>

                <?php
                while ($row = $result->fetch_assoc()) {

                  $singleBlood_result = $blood_object->give_SpecificGroup($row['blood_grp']);
                  $singleBlood_row = $singleBlood_result->fetch_assoc();

                ?>
                  <tr>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td><?php echo $row['donor_name']; ?></td>
                    <td><?php echo $row['donor_nic']; ?></td>
                    <td><?php echo $singleBlood_row["grp_name"]; ?></td>
                    <td><a href="../report/DisplayDonor_report.php?donor_id=<?php echo base64_encode($row['donor_id']); ?>" class="btn btn-danger btn-sm"><u><i class="fa fa-file-pdf"></i> View Report</u></a></td>
                    <td>
                      <?php if ($row['donor_status'] == 1) {
                        $status = "<span style='color:#090;'>Active</span>";
                      } else {
                        $status = "<span style='color:#F00;'>Blocked</span>";
                      }
                      echo $status; ?>
                    </td>
                    <td>
                      <a href="display_donor.php?donor_id=<?php echo base64_encode($row['donor_id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> View/Edit</a>&nbsp;
                      <?php if ($row['donor_status'] == 1) { ?>
                        <a href="../controller/donor_controller.php?status=active_block&donor_id=<?php echo $row['donor_id']; ?>&donor_status=<?php echo $row['donor_status']; ?>" class="btn btn-warning btn-sm BlockConf"><i class="fa fa-user-lock"></i> Block</a>
                      <?php } else { ?>
                        <a href="../controller/donor_controller.php?status=active_block&donor_id=<?php echo $row['donor_id']; ?>&donor_status=<?php echo $row['donor_status']; ?>" class="btn btn-success btn-sm"><i class="fa fa-lock-open"></i> Activate</a>
                      <?php } ?> &nbsp;
                      <a href="../controller/donor_controller.php?status=removeDon&donor_id=<?php echo $row['donor_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>
              <a href="../report/ListedDonors_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download</a>
            </div>
          </div>
        </div>

      </div>

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script>
    $(document).ready(function(e) {
      ///////////////////////////// Enable data tables plugin ///////////////////////////
      $('#mng_donorTB').DataTable();

      ///////////////////////////// Block confirmation ///////////////////////////
      $('#mng_donorTB').on('click', '.BlockConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Block This Donor ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Block!",
            cancelButtonClass: "btn-info",
            closeOnConfirm: true
          },
          function(isConfirm) {
            if (isConfirm === true) {
              window.location.href = loc;
            }
          });

        return false;

      });

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#mng_userTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Remove This Donor ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Remove",
            cancelButtonClass: "btn-info",
            closeOnConfirm: true
          },
          function(isConfirm) {
            if (isConfirm === true) {
              window.location.href = loc;
            }
          });

        return false;

      });


    });
  </script>
</body>

</html>