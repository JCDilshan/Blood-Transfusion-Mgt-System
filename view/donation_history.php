<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/donation_model.php');
$donHistory_obj = new Donation($conn);
$result = $donHistory_obj->give_History();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Donations History</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>
  <style type="text/css">
    .tbhead {
      background: #666;
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

      <!--2nd Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-clock-rotate-left fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Donation History</b></span>
            </div>
            <div class="card-body bg-light">
              <h6 style="color:#FF5959;">(View Only)</h6>
              <table class="table table-bordered table-striped text-center" id="donHisTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Donated Date</td>
                    <td>#Donor ID</td>
                    <td>Donor Name</td>
                    <td>#Bag ID</td>
                    <td>#Camp ID</td>
                    <td>#Hospital ID</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['donated_date']; ?></td>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td>
                      <?php
                      include_once('../model/donor_model.php');
                      $donor_obj = new Donor($conn);
                      $result_2 = $donor_obj->get_SpecificDonor($row['donor_id']);
                      $row_2 = $result_2->fetch_assoc();
                      echo $row_2['donor_name'];
                      ?>
                    </td>
                    <td><?php echo $row['bag_id']; ?></td>
                    <td><?php echo $row['camp_id']; ?></td>
                    <td><?php echo $row['hospital_id']; ?></td>
                  </tr>
                <?php } ?>

              </table>

              <a href="../report/DonHistory_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download Report</a>
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


  <script>
    $(document).ready(function(e) {
      /////////////////////////// Enable data tables plugin ///////////////////////////
      $('#donHisTB').DataTable();
    });
  </script>
</body>

</html>