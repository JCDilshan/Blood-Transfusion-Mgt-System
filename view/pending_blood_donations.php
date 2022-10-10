<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 5) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/donation_model.php');
$donation_obj = new Donation($conn);
$result = $donation_obj->All_donations();

include('../model/donor_model.php');
$donor_object = new Donor($conn);

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pending Donations</title>

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
  <div class="wrapper d-flex align-items-stretch">

    <?php include('../includes/nav_bar.php'); ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>
      <!--Content Start-->

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <span style="font-size:28px; letter-spacing:5px;"><b>Pending Blood Donation</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="pendFormsTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Donated Date</td>
                    <td>#Bag ID</td>
                    <td>#Donor NIC</td>
                    <td>#Camp ID</td>
                    <td>#Hospital ID</td>
                    <td>Blood Group</td>
                    <td style="width: 18%;">Recieved Time</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) {
                  if ($row['proc_status'] == 0) { ?>
                    <tr>
                      <td><?php echo $row['donated_date']; ?></td>
                      <td><?php echo $row['bag_id']; ?></td>
                      <td><?php echo $row['donor_nic']; ?></td>
                      <td><?php echo $row['camp_id']; ?></td>
                      <td><?php echo $row['hospital_id']; ?></td>
                      <td><?php echo $row['grp_name']; ?></td>
                      <td><?php echo date("jS M Y g:i A", strtotime($row['sent_date']));; ?></td>
                      <td>
                        <a href="../controller/donation_controller.php?status=take_proceed&donation_id=<?php echo $row['donation_id']; ?>" class="btn btn-warning btn-sm mt-1"><i class="fa fa-share"></i> Take Proceed</a>

                        <a href="../controller/donation_controller.php?status=remove_donation&donation_id=<?php echo $row['donation_id']; ?>&bag_id=<?php echo $row['bag_id']; ?>&donor_id=<?php echo $row['donor_id']; ?>" class="btn btn-danger btn-sm mt-1 RemoveConf"><i class="fa fa-times"></i> Delete Donation</a>
                      </td>
                    </tr>
                <?php }
                } ?>

              </table>
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
      $('#pendFormsTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#pendFormsTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Remove This Donoation ?",
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