<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/blood_model.php');
$bloodComp_object = new Blood_Component($conn);
$result = $bloodComp_object->give_Allcomponents();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Blood Components</title>

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
      <div class="row pl-3 mt-4">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <i class="fa fa- fa-3x fa-pull-left"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>Listed Blood Components</b></span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center" id="mng_compTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Component ID</td>
                    <td>Component Type</td>
                    <td>Description</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['comp_id']; ?></td>
                    <td><?php echo $row['comp_name']; ?></td>
                    <td><?php if (!empty($row['description'])) {
                          echo $row['description'];
                        } else {
                          echo "No Description";
                        } ?></td>
                    <td>
                      <a href="EditBlood_Comp.php?status=updateComp&comp_id=<?php echo base64_encode($row['comp_id']); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>
            </div>
          </div>

        </div>

      </div>
      <!--2nd Row End-->

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>

</body>

</html>