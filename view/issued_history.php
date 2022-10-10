<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 3) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/inventory_model.php');
$issueHistory_obj = new Inventory($conn);
$result = $issueHistory_obj->get_AllIssuedHistory();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Issued History</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>
  <style type="text/css">
    .tbhead {
      background: #666;
      color: #FFF;
    }

    .view_bagTB {
      background-color: #006;
      color: #FFF !important;
    }

    .view_bagTB tr td {
      padding-left: 10px;
      font-weight: bold;
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
              <span style="font-size:28px; letter-spacing:5px;"><b>Blood Issued History</b></span>
            </div>
            <div class="card-body bg-light">
              <h6 style="color:#FF5959;">(View Only)</h6>
              <table class="table table-bordered table-striped text-center" id="issueHisTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Bag ID</td>
                    <td>#Patient ID</td>
                    <td>Patient Name</td>
                    <td>Hospital</td>
                    <td>Issued Date</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td>
                      <?php echo $row['bag_id']; ?>&nbsp;
                      <button type="button" value="<?php echo $row['bag_id']; ?>" class="viewBag btn btn-link btn-sm"><u>View Bag Details</u></button>
                    </td>
                    <td><?php echo $row['patient_id']; ?></td>
                    <td>
                      <?php
                      include_once('../model/patient_model.php');
                      $pat_obj = new Patient($conn);
                      $result_2 = $pat_obj->get_SpecificPatient($row['patient_id']);
                      $row_2 = $result_2->fetch_assoc();
                      echo $row_2['patient_name'];
                      ?>
                    </td>
                    <td><?php echo $row['hospital']; ?></td>
                    <td><?php echo $row['issued_date']; ?></td>
                  </tr>
                <?php } ?>

              </table>

              <a href="../report/IssuedHistory_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download Report</a>
            </div>
          </div>

        </div>

      </div>
      <!--Row End-->

      <div>&nbsp;</div>

      <!--Modal Start-->
      <div class="modal fade" id="view_bag" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-center">Blood Bag Details</h5>
            </div>
            <div class="modal-body">
              <div class="row">
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table class="view_bagTB" border="1" style="width:100%;">
                    <tr>
                      <td>Bag ID</td>
                      <td id="bag_id"></td>
                    </tr>
                    <tr>
                      <td>Blood Group</td>
                      <td id="blood_grp"></td>
                    </tr>
                    <tr>
                      <td>Component Type</td>
                      <td id="comp_type"></td>
                    </tr>
                    <tr>
                      <td>Donor ID</td>
                      <td id="donor_id"></td>
                    </tr>
                    <tr>
                      <td>Camp ID</td>
                      <td id="camp_id"></td>
                    </tr>
                    <tr>
                      <td>Hospital ID</td>
                      <td id="hosp_id"></td>
                    </tr>
                  </table>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!--Modal End-->

  </div>

  <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script>
    $(document).ready(function(e) {
      /////////////////////////// Enable data tables plugin ///////////////////////////
      $('#issueHisTB').DataTable();

      /////////////////////////// View blood bag details by ajax ///////////////////////////
      $('#issueHisTB').on('click', '.viewBag', function() {

        var bag_id = $(this).val();

        $.ajax({
          url: "../controller/inventory_controller.php?status=get_bagDetails",
          type: 'POST',
          dataType: "JSON",
          data: {
            bag_id: bag_id
          },
          success: function(res) {
            if (res.bag_id != "") {
              $('#view_bag').modal('show');
              $('#bag_id').text(bag_id);
              $('#blood_grp').text(res.grp_name);
              $('#comp_type').text(res.comp_name);
              $('#donor_id').text(res.donor_id);
              $('#camp_id').text(res.camp_id);
              $('#hosp_id').text(res.hospital_id);
            } else {
              swal("No Data Found !", "The Bag May Have Been Removed Or Something Went Wrong...", "error");
            }
          },
          error: function() {
            swal("oops ! something went wrong..", "Please Try Again Later", "error");
          }
        });

      });

    });
  </script>
</body>

</html>