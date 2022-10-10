<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 5) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/donation_model.php');
$TestedDonation_obj = new Donation($conn);
$result = $TestedDonation_obj->get_TestedDonationHistory();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Checked Donations</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>

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
              <span style="font-size:28px; letter-spacing:5px;"><b>Checked History</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="check_ResultTB" style="width: 100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Bag ID</td>
                    <td>Checked Date</td>
                    <td>#Donor ID</td>
                    <td>#Camp ID</td>
                    <td style="width:14%;">#Hospital ID</td>
                    <td>Status</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['bag_id']; ?></td>
                    <td><?php echo $row['checked_date']; ?></td>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td><?php echo $row['camp_id']; ?></td>
                    <td><?php echo $row['hospital_id']; ?></td>
                    <td>
                      <?php if ($row['check_status'] == 1) {
                        echo "<span style='color:#0F0;'><b>Accepted</span></b>";
                      } else {
                        echo "<span style='color:#F00;'><b>Rejected</span></b>";
                      } ?>
                    </td>
                    <td>
                      <a href="../controller/donation_controller.php?status=remove_CheckResult&record_id=<?php echo $row['record_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times"></i> Remove</a>
                    </td>
                  </tr>
                <?php } ?>

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
      /////////////////////////// Enable data tables plugin ///////////////////////////
      $('#check_ResultTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#check_ResultTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Confirm To Remove Record",
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