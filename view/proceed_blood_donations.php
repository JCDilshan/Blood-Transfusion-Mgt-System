<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 5) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/donation_model.php');
$procDonation_obj = new Donation($conn);
$result = $procDonation_obj->get_ProcDonations();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Proceed Donations</title>

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

          <div class="cardpanel-dark">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <span style="font-size:28px; letter-spacing:5px;"><b>Test Proceeding Donations</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="proc_DonTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Recieved Time</td>
                    <td>#Donation ID</td>
                    <td>Bag ID</td>
                    <td>Donor NIC</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['last_updated']; ?></td>
                    <td><?php echo $row['donation_id']; ?></td>
                    <td><?php echo $row['bag_id']; ?></td>
                    <td><?php echo $row['donor_nic']; ?></td>
                    <td>
                      <a href="../controller/donation_controller.php?status=proc_Rollback&donation_id=<?php echo $row['donation_id']; ?>" class="btn btn-warning btn-sm RollbackConf"><i class="fa fa-arrow-left"></i> Rollback</a>
                      &nbsp;
                      <a href="display_ProcDonationForm.php?donation_id=<?php echo base64_encode($row['donation_id']); ?>" class="btn btn-info btn-sm"> Finalize <i class="fa fa-arrow-right"></i></a>
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
      $('#proc_DonTB').DataTable();

      ///////////////////////////// Rollback confirmation ///////////////////////////
      $('#proc_DonTB').on('click', '.RollbackConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Rollback This Process ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes !",
            cancelButtonClass: "btn-info",
            cancelButtonText: "No",
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