<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 4) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/patient_model.php');
$bloodReq_obj = new Blood_Request($conn);
$result_acc = $bloodReq_obj->get_AllApprovedRequests();
$result_rej = $bloodReq_obj->get_AllRejectedRequests();

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
              <span style="font-size:28px; letter-spacing:5px;"><b>Checked Blood Requests</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered text-center" id="checked_reqTB" style="width: 100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Request ID</td>
                    <td>Status</td>
                    <td>Checked date</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row_acc = $result_acc->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row_acc['request_id']; ?></td>
                    <td><?php echo "<span style='color:#0C0;'>Accepted</span>"; ?></td>
                    <td><?php echo $row_acc['checked_date']; ?></td>
                    <td>
                      <a href="display_CheckedRequests.php?request_id=<?php echo base64_encode($row_acc['request_id']); ?>&checkStatus=<?php echo base64_encode("accept"); ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View Form</a>
                      <a href="../controller/patient_controller.php?status=remove_CheckedReq&req_id=<?php echo $row_acc['request_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times"></i> Delete Record</a>
                    </td>
                  </tr>
                <?php } ?>

                <?php while ($row_rej = $result_rej->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row_rej['request_id']; ?></td>
                    <td><?php echo "<span style='color:#F00;'>Rejected</span>"; ?></td>
                    <td><?php echo $row_rej['checked_date']; ?></td>
                    <td>
                      <a href="display_CheckedRequests.php?request_id=<?php echo base64_encode($row_rej['request_id']); ?>&checkStatus=<?php echo base64_encode("reject"); ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View Form</a>
                      <a href="../controller/patient_controller.php?status=remove_CheckedReq&req_id=<?php echo $row_rej['request_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times"></i> Delete Record</a>
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
      $('#checked_reqTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#checked_reqTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want To Delete This Record ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Delete",
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