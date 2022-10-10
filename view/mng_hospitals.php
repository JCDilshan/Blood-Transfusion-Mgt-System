<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/schedule_model.php');
$hospital_obj = new Hospital($conn);
$result = $hospital_obj->give_AllHospitals();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Venues(Hospitals)</title>

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

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <div class="row pl-3">
        <button id="add_hospBtn" class="btn btn-dark">Add New Branch <i class="fa fa-plus-circle"></i></button>
      </div>

      <!--Row Start-->
      <div class="row mt-2" id="fRow">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-store-alt fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Add Branch</b></span>
            </div>
            <div class="card-body">
              <!--Form Start-->
              <form id="addHosp_form" action="../controller/schedule_controller.php?status=add_hospital" method="post">

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Enter Hospital(Branch) Name :</label>
                      <input id="hosp_name" name="hosp_name" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Location :</label>
                      <input id="location" name="location" type="text" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Contact No. :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="con_no" name="con_no" type="number" class="form-control" autocomplete="off" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="alert-secondary">Email :</label>
                      <input id="email" name="email" type="email" class="form-control" autocomplete="off" required>
                    </div>
                  </div>

                </div>

                <h4 id="hosp_res" style="color:#F00;"></h4>

                <button id="submit" type="submit" class="btn btn-success"><b><i class="fa fa-plus"></i> Add </b></button>

              </form>
            </div>
          </div>

        </div>

      </div>
      <!--Row End-->

      <hr>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF; background-color:#CCC;">
              <i class="fa fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Hospitals(Branches)</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="mng_hospTB" style="width:100%;">
                <thead>
                  <tr class="tbhead">
                    <td>#Hospital ID</td>
                    <td>Name</td>
                    <td>Location</td>
                    <td>Contact NO.</td>
                    <td style="width:22%;">Action</td>
                  </tr>
                </thead>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['hospital_id']; ?></td>
                    <td><?php echo $row['hospital_name']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['contact_no']; ?></td>
                    <td>
                      <button value="<?php echo $row['hospital_id']; ?>" class="btn btn-info btn-sm edit"><i class="fa fa-edit"></i> View/Edit</button>&nbsp;
                      <a href="../controller/schedule_controller.php?status=remove_hospital&hosp_id=<?php echo $row['hospital_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
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
      <!--Row End-->

      <!--Modal Start-->
      <div class="modal fade" id="update_hosp" role="dialog">
        <div class="modal-dialog modal-md">
          <form id="updateHosp_form" action="../controller/schedule_controller.php?status=update_hospital" method="post">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-center">Update Details Here</h5>
              </div>
              <div class="modal-body">
                <div class="row">
                </div>
                <div class="row">&nbsp;</div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="hidden" id="hosp_id" name="hosp_id">
                      <label class="alert-dark">New Contact :</label>
                      <div class="input-group">
                        <span class="input-group-prepend">
                          <span class="input-group-text">0</span>
                        </span>
                        <input id="up_conno" name="up_conno" type="number" class="form-control" autocomplete="off" required>
                      </div>
                    </div>
                  </div>
                </div>

                <div>&nbsp;</div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-dark">New Email :</label>
                      <input id="up_email" name="up_email" type="email" class="form-control" autocomplete="off" required>
                    </div>
                  </div>
                </div>

                <div>&nbsp;</div>

                <button type="submit" class="btn btn-success">Save Changes <i class="fa fa-save-alt"></i></button>
              </div>
            </div>

        </div>
      </div>
      </form>
    </div>
  </div>
  <!--Modal End-->


  </div>

  </div>
  </div>


  <script src="../js/validations/hospital_val.js"></script>
</body>

</html>