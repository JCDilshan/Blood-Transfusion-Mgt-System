<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 5) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/donation_model.php');
$TestedDonations_obj = new Donation($conn);
$result = $TestedDonations_obj->get_InventoryPass();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Tested Forms</title>

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

      <!--2nd Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <span style="font-size:28px; letter-spacing:5px;"><b>Tested Blood</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="InvenPassTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>Bag Sealed Date</td>
                    <td>#Bag ID</td>
                    <td>#Donor ID</td>
                    <td>Donor NIC</td>
                    <td>Blood Group</td>
                    <td>Component Type</td>
                    <td style="width:20%;">Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['sealed_date']; ?></td>
                    <td><?php echo $row['bag_id']; ?></td>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td><?php echo $row['donor_nic']; ?></td>
                    <td><?php echo $row['grp_name']; ?></td>
                    <td><?php echo $row['comp_name']; ?></td>
                    <td>
                      <a href="add_blood.php?bag_id=<?php echo base64_encode($row['bag_id']); ?>" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Add To Inventory</a>
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
      $('#InvenPassTB').DataTable();
    });
  </script>
</body>

</html>