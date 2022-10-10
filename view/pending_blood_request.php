<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 4) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/patient_model.php');
$bloodReq_obj = new Blood_Request($conn);
$result = $bloodReq_obj->give_PendingRequests();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Pending Requests</title>

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
              <i class="fa fa-file-medical fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Blood Request Forms</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="pend_reqTB" style="width: 100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Priority</td>
                    <td>#Request ID</td>
                    <td>Patient N.I.C No</td>
                    <td>Recieved Date</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td class="font-weight-bold">
                      <?php if ($row['priority'] == 1) {
                        echo "<span class='text-danger'>1 - Emergency</span>";
                      } else if ($row['priority'] == 2) {
                        echo "<span class='text-warning'>2 - Urgent</span>";
                      } else {
                        echo "<span class='text-success'>3 - Routing</span>";
                      } ?>
                    </td>
                    <td><?php echo $row['request_id']; ?></td>
                    <td><?php echo $row['nic_no']; ?></td>
                    <td><?php echo date("jS M Y g:i A", strtotime($row['added'])); ?></td>
                    <td>
                      <a href="display_PendReqForm.php?request_id=<?php echo base64_encode($row['request_id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-arrow-left-circle"></i> Take For Checking</a> &nbsp;
                      <a href="#" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times"></i> Delete Form</a>
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
      $('#pend_reqTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#pend_reqTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want To Delete This Request Form ?",
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