<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include_once('../model/schedule_model.php');
$hospital_obj = new Hospital($conn);
$result_hos = $hospital_obj->give_AllHospitals();

include_once('../model/schedule_model.php');
$schedule_object = new Schedule($conn);
$result = $schedule_object->give_ListedSchedule();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Schedule</title>

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
        <button id="add_schedBtn" class="btn btn-dark">Add New Schedule <i class="fa fa-plus-circle"></i></button>
      </div>

      <!--Row Start-->
      <div class="row mt-2" id="fRow">

        <div class="col-md-8 offset-2">

          <div class="card">
            <div class="card-header text-center bg-dark text-white">
              <i class="fa fa-clipboard-list fa-3x float-left"></i> <span style="font-size:28px; letter-spacing:5px;"><b>Add Schedule</b></span>
            </div>
            <div class="card-body">
              <!--Reg Form Start-->
              <form id="add_schedule" action="../controller/schedule_controller.php?status=add_schedule" method="post">

                <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="alert-secondary">Location(Venue) :</label>
                      <select id="venue" name="venue" class="form-control" required>
                        <option value="">Select Location...</option>
                        <?php while ($array = $result_hos->fetch_assoc()) { ?>
                          <option value="<?php echo $array['hospital_id']; ?>"><?php echo $array['hospital_name'] . " - " . $array['location']; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4" id="single_date">
                    <div class="form-group">
                      <label class="alert-secondary">Date :</label>
                      <input id="date" name="date" value="" type="date" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">Start Time :</label>
                      <input id="str_time" type="time" name="str_time" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">End Time :</label>
                      <input id="end_time" type="time" name="end_time" class="form-control" required readonly>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="alert-secondary">Slots :</label>
                      <input id="slots" name="slots" type="number" class="form-control" required>
                    </div>
                  </div>

                </div>

                <h4 style="color:#F00;" id="schedule_res"></h4>


                <button type="button" class="btn btn-info btn-sm" id="set_series">Set Series Of Dates <i class="fa fa-plus-circle"></i></button>


                <div class="row mt-1" id="dt_seriesRow">

                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="alert-secondary">From :</label>
                      <input id="from_date" name="from_date" type="date" value="" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="alert-secondary">To :</label>
                      <input id="to_date" type="date" name="to_date" value="" class="form-control" readonly>
                    </div>
                  </div>

                </div>

                <hr>

                <button id="submit" type="submit" class="btn btn-success"><b><i class="fa fa-plus"></i> Add Schedule</b></button>

              </form>
              <!--Reg Form End-->
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
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Schedules</b></span>
            </div>
            <div class="card-body bg-light">
              <table class="table table-bordered table-striped text-center" id="mng_scheduleTB" style="width:100%;">
                <thead>
                  <tr class="tbhead">
                    <td>#Schedule ID</td>
                    <td>Venue</td>
                    <td style="width: 10%;">Date</td>
                    <td>Start Time</td>
                    <td>Total Slots</td>
                    <td>Available Slots</td>
                    <td style="width:23%;">Action</td>
                  </tr>
                </thead>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['schedule_id']; ?></td>
                    <td>
                      <?php
                      $result_2 = $hospital_obj->get_SpecificHospital($row['hospital_id']);
                      $row_2 = $result_2->fetch_assoc();
                      echo $row_2['hospital_name'] . " - " . $row_2['location']; ?>
                    </td>
                    <td><?php echo $row['set_date']; ?></td>
                    <td><?php echo date("g:i A", strtotime($row['set_date'] . " " . $row['start_time'])); ?></td>
                    <td><?php echo $row['total_slots']; ?></td>
                    <td><?php echo $row['av_slots']; ?></td>
                    <td>
                      <a href="display_EditSchedule.php?sched_id=<?php echo base64_encode($row['schedule_id']); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit Slots</a>
                      <a href="../controller/schedule_controller.php?status=remove_schedule&schedule_id=<?php echo $row['schedule_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
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

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/schedule_val.js"></script>
</body>

</html>