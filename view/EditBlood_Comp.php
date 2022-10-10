<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$comp_id = isset($_REQUEST['comp_id']) ? base64_decode($_REQUEST['comp_id']) : '';

include('../model/blood_model.php');
$bloodComp_object = new Blood_Component($conn);
$result = $bloodComp_object->give_SpecificComp($comp_id);
$row = $result->fetch_assoc();

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

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--1st Row Start-->
      <div class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Add Blood Group</b></span>
            </div>
            <div class="card-body">
              <form action="../controller/blood_controller.php?status=updateComp&comp_id=<?php echo $comp_id; ?>" method="post">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-dark">Description :</label>
                      <textarea name="description" class="form-control"><?php echo $row['description']; ?></textarea>
                    </div>
                  </div>

                </div>

                <button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-plus-square"></span> Save Changes</b></button>

              </form>
            </div>
          </div>

        </div>

      </div>
      <!--1st Row End-->

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


</body>

</html>