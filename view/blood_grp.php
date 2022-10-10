<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include_once('../model/blood_model.php');
$bloodGrp_object = new Blood_Grp($conn);

$result = $bloodGrp_object->give_Allgroups();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Add Blood Group</title>

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

    <!-- ///////////////////////// Content start ///////////////////////// -->
    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!-- ///////////////////////// Blood group list start ///////////////////////// -->
      <div class="row pl-3 mt-4">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <i class="fa fa- fa-3x fa-pull-left"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>Listed Blood Groups</b></span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center" style="width:100%;" id="mng_grpTB">
                <thead class="tbhead">
                  <tr>
                    <td>#Group ID</td>
                    <td>Blood Group</td>
                    <td>Description</td>
                    <td>Action</td>
                  </tr>
                </thead>

                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['grp_id']; ?></td>
                    <td><?php echo $row['grp_name']; ?></td>
                    <td><?php if (!empty($row['description'])) {
                          echo $row['description'];
                        } else {
                          echo "No Description";
                        } ?></td>
                    <td>
                      <a href="EditBlood_grp.php?grp_id=<?php echo base64_encode($row['grp_id']); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
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
      <!-- ///////////////////////// Blood group list end ///////////////////////// -->

    </div>
    <!-- ///////////////////////// Content end ///////////////////////// -->

  </div>
  <!-- ///////////////////////// Wrapper end ///////////////////////// -->

  </div>

</body>

</html>