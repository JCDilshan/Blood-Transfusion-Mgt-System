<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 3) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/patient_model.php');
$bloodReq_obj = new Blood_Request($conn);
$result = $bloodReq_obj->get_IssuePendingRequests();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Checked Requests</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>


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

      <!--2nd Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <span style="font-size:28px; letter-spacing:5px;"><b>Blood Tested Forms</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered text-center" id="issue_bloodTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Priority</td>
                    <td>#Req ID</td>
                    <td>Blood Group</td>
                    <td>Require Amount(Bags)</td>
                    <td>Received Date</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td>
                      <?php if ($row['priority'] == 1) {
                        echo "<span style='color:#F00;'>1 (Emergency)</span>";
                      } else if ($row['priority'] == 2) {
                        echo "<span style='color:#F90;'>2 (Urgent)</span>";
                      } else {
                        echo "<span style='color:#46FF46;'>3 (Routing)</span>";
                      } ?>
                    </td>
                    <td><?php echo $row['request_id']; ?></td>
                    <td><?php echo $row['blood_grp']; ?></td>
                    <td><?php echo $row['require_amount']; ?></td>
                    <td><?php echo $row['checked_date']; ?></td>
                    <td>

                      <a href="issue_blood.php?request_id=<?php echo base64_encode($row['request_id']); ?>" class="btn btn-warning btn-sm"><i class="fa fa-share"></i>Take To Issue</a>
                      <a href="#" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Remove Request</a>
                    </td>
                  </tr>
                <?php } ?>

              </table>
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
      $('#issue_bloodTB').DataTable();
    });
  </script>
</body>

</html>