<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/camp_model.php');
$camp_object = new Camp($conn);
$result = $camp_object->getAllcamps();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Camp Details</title>

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

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-store-alt fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Blood Camps</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" style="width:100%;" id="mng_campTB">
                <thead class="tbhead">
                  <tr>
                    <td>#Camp ID</td>
                    <td>Organizer Name</td>
                    <td>Location</td>
                    <td style="width: 20%;">Date & Time</td>
                    <td>Status</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['camp_id']; ?></td>
                    <td><?php echo $row['organizer_name']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo date("jS M Y g:i A", strtotime($row['date'] . " " . $row['start_time'])); ?></td>

                    <td>
                      <?php if ($row['held_status'] == 1 && $row["partici_donors"] != NULL) { ?>
                        <span class="text-success">Completed</span>
                      <?php } else if ($row['held_status'] == 0) { ?>
                        <a href="../controller/camp_controller.php?status=set_held&camp_id=<?php echo $row['camp_id']; ?>" class="btn btn-success btn-sm mt-1">Set as Held</a>
                      <?php } else if ($row['held_status'] == 1 && $row["partici_donors"] == NULL) { ?>
                        <button value="<?php echo $row['camp_id']; ?>" class="btn btn-info btn-sm finalize"><i class="fa fa-exclamation"></i> Finalize Pending</button>
                      <?php } ?>
                    </td>

                    <td>
                      <?php if ($row['held_status'] == 1 && $row["partici_donors"] != NULL) { ?>
                        <a href="../report/campFinal_report.php?camp_id=<?php echo $row['camp_id']; ?>" class="btn btn-danger btn-sm mt-1"><i class="fa fa-file-pdf"></i> View Report</a>
                      <?php } else if ($row['held_status'] == 0) { ?>
                        <a href="edit_campDetails.php?camp_id=<?php echo base64_encode($row['camp_id']); ?>" class="btn btn-warning btn-sm mt-1"><i class="fa fa-edit"></i> View/Edit </a>
                      <?php } ?>
                      <a href="../controller/camp_controller.php?status=remove_camp&camp_id=<?php echo $row['camp_id']; ?>" class="btn btn-danger btn-sm RemoveConf mt-1"><i class="fa fa-times-circle"></i> Remove</a>
                    </td>
                  </tr>
                <?php
                } ?>

              </table>

              <a href="../report/ListedCamps_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download</a>
            </div>
          </div>

        </div>

      </div>


      <div class="modal fade" id="camp_finalize" role="dialog">
        <div class="modal-dialog modal-md">
          <form id="FinalizeForm" enctype="multipart/form-data" action="../controller/camp_controller.php?status=finalize" method="post">
            <div class="modal-content">
              <div class="modal-body">
                <div class="row">
                </div>
                <div class="row">&nbsp;</div>

                <div>&nbsp;</div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input id="camp_id" name="camp_id" type="hidden" class="form-control" required>
                      <label class="alert-dark">Enter Participated Donors :</label>
                      <input type="number" name="par_donors" class="form-control" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-dark">Remark :</label>
                      <textarea name="remark" id="remark" class="form-control" placeholder="Type your comment" required></textarea>
                    </div>
                  </div>
                </div>

                <div>&nbsp;</div>

                <button type="submit" class="btn btn-success">Finalize <i class="fa fa-checked"></i></button>
              </div>
            </div>

        </div>
      </div>
      </form>
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
      $('#mng_campTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#mng_campTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Cacel This Blood Camp ?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Cancel",
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

      ///////////////////////////// Camp finalization modal show ///////////////////////////
      $('#mng_campTB').on('click', '.finalize', function() {

        var camp_id = $(this).val();
        $('#camp_id').val(camp_id);

        $('#camp_finalize').modal('show');

      });

    });
  </script>
</body>

</html>