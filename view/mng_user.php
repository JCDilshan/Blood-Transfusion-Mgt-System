<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/user_model.php');
$user_object = new User($conn);
$result = $user_object->give_Allusers();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Users</title>

  <style type="text/css">
    .tbhead {
      background-color: #666;
      color: #FFF;
    }
  </style>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>

</head>

<body>
  <div class="wrapper d-flex align-items-stretch">

    <?php
    include('../includes/nav_bar.php');
    ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <div class="row pl-3">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <i class="fa fa-users fa-3x fa-pull-left"></i> <i class="fa fa-edit fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Listed Users</b></span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped text-center" style="width:100%;" id="mng_userTB">
                <thead class="tbhead">
                  <tr>
                    <td>#User ID</td>
                    <td>User Name</td>
                    <td>User Role</td>
                    <td>Status</td>
                    <td style="width: 18%;">Last Online</td>
                    <td style="width: 32%;">Action</td>
                  </tr>
                </thead>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                  <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                    <td><?php
                        include_once('../model/role_model.php');
                        $role_object = new Role($conn);
                        $result_role = $role_object->get_specificRole($row['user_role']);
                        $row_role = $result_role->fetch_assoc();
                        echo $row_role['role_name']; ?>
                    </td>
                    <td>
                      <?php if ($row['user_status'] == 1) {
                        $status = "<span style='color:#090;'>Active</span>";
                      } else {
                        $status = "<span style='color:#F00;'>Blocked</span>";
                      }
                      echo $status; ?>
                    </td>
                    <td><?php if ($row['login_status'] == 0) {
                          echo date("j M Y g:i A", strtotime($row['last_logout']));
                        } else {
                          echo "<span style='color:#0F0;'>Online <span style='font-size:20px;'>&bull;</span></span>";
                        } ?></td>

                    <td>
                      <a href="display_UserDetails.php?user_id=<?php echo base64_encode($row['user_id']); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> View/Edit</a> &nbsp;
                      <?php if (($_SESSION['user_role'] == 100 && $row["user_role"] != 100) || ($row["user_role"] != 1 && $row["user_role"] != 100)) {
                        if ($row['user_status'] == 1) { ?>
                          <a href="../controller/user_controller.php?status=active_block&user_id=<?php echo $row['user_id']; ?>&user_status=<?php echo $row['user_status']; ?>" class="btn btn-warning btn-sm BlockConf"><i class="fa fa-user-lock"></i> Block</a>
                        <?php } else { ?>
                          <a href="../controller/user_controller.php?status=active_block&user_id=<?php echo $row['user_id']; ?>&user_status=<?php echo $row['user_status']; ?>" class="btn btn-success btn-sm"><i class="fa fa-lock-open"></i> Activate</a>
                        <?php } ?> &nbsp;
                        <a href="../controller/user_controller.php?status=remove_user&user_id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
                      <?php } ?>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>

              <a href="../report/user_report.php" class="btn btn-danger"><i class="fa fa-file-pdf"></i> View / Download</a>
            </div>
          </div>

        </div>

      </div>
      <!--Row End-->

      <div>&nbsp;</div>

    </div>

  </div>
  </div>
</body>
<!-- ///////////////////////// Content end ///////////////////////// -->


<script type="text/javascript">
  $(document).ready(function(e) {

    ///////////////////////////// Enable data tables plugin ///////////////////////////
    $('#mng_userTB').DataTable();

    ///////////////////////////// Block confirmation ///////////////////////////
    $('#mng_userTB').on('click', '.BlockConf', function() {

      var loc = $(this).attr('href');

      swal({
          title: "Are you sure You Want Block This User ?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Block!",
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

    ///////////////////////////// Remove confirmation ///////////////////////////
    $('#mng_userTB').on('click', '.RemoveConf', function() {

      var loc = $(this).attr('href');

      swal({
          title: "Are you sure You Want Remove This User ?",
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