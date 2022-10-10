<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/camp_model.php');
$camp_object = new Camp($conn);
$result = $camp_object->getAllHeldcamps();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Mng Camp Reports</title>

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

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-file fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Camp Finalized Reports</b></span>
            </div>
            <div class="card-body bg-light">
              <h6 style="color:#FF5959;">(View Only)</h6>
              <table class="table table-bordered table-striped text-center text-center" id="mng_campRepTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Held date</td>
                    <td>#Camp ID</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) {
                  if ($row['partici_donors'] != NULL) { ?>
                    <tr>
                      <td><?php echo $row['date']; ?></td>
                      <td><?php echo $row['camp_id']; ?></td>
                      <td><a href="../report/campFinal_report.php?camp_id=<?php echo $row['camp_id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> View / Download</a>
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
      $('#mng_campRepTB').DataTable();
    });
  </script>
</body>

</html>