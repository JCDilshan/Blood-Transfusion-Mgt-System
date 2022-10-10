<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/patient_model.php');
$patient_object = new Patient($conn);
$result = $patient_object->give_Allpatients();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Patients</title>

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

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-bed-pulse fa-3x fa-pull-left"></i> </i> <span style="font-size:28px; letter-spacing:5px;"><b>View Listed Patients</b></span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center" id="mng_patientTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Patient ID</td>
                    <td>Patient Name</td>
                    <td>N.I.C. No</td>
                    <td>Blood Group</td>
                    <td>Age</td>
                    <td>Reports</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['patient_id']; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['nic_no']; ?></td>
                    <td><?php echo $row['grp_name']; ?></td>
                    <td><?php echo $row['age'] . " (At " . date('Y', strtotime($row['last_updated'])) . ")"; ?></td>
                    <td><a href="../report/DisplayPatient_report.php?patient_id=<?php echo base64_encode($row['patient_id']); ?>" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf"></i> <u>View Report</u></a></td>
                    <td>
                      <a href="display_patient.php?patient_id=<?php echo base64_encode($row['patient_id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> View/Edit</a> &nbsp;
                      <a href="../controller/patient_controller.php?status=removePat&patient_id=<?php echo $row['patient_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>

              <a class="btn btn-danger" href="../report/ListedPatients_report.php"><i class="fa fa-file-pdf"></i> View / Download</a>
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
      $('#mng_patientTB').DataTable();

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#mng_patientTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want To Remove This Patient ?",
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