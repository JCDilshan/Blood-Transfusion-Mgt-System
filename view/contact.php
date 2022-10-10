<?php
include('../includes/session&redirect.php');

include('../model/user_model.php');
$user_object = new User($conn);
$result_user = $user_object->give_Allusers();

include('../model/staff_model.php');
$doctor_object = new Doctor($conn);
$result_doc = $doctor_object->get_Alldoctors();
$nurse_object = new Nurse($conn);
$result_nurse = $nurse_object->get_AllNurse();
$member_object = new Other_Staff($conn);
$result_mem = $member_object->get_AllMembers();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Contact</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');

  ?>

  <style type="text/css">
    .tbhead {
      background: #000;
      color: #FFF;
      font-weight: bolder;
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

      <!--Row Start-->
      <div class="row">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-body bg-light">

              <table class="table table-bordered table-striped text-center" id="mng_contactTB" style="width:100%;">
                <thead class="tbhead">
                  <tr style="height:50px;">
                    <td><i class="fa fa-2x fa-user"></i></td>
                    <td><i class="fa fa-2x fa-suitcase"></i></td>
                    <td><i class="fa fa-2x fa-mobile-alt"></i></td>
                  </tr>
                </thead>

                <?php while ($row = $result_user->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
                    <td>
                      <?php
                      include_once('../model/role_model.php');
                      $role_object = new Role($conn);
                      $role_result = $role_object->get_specificRole($row['user_role']);
                      $role_row = $role_result->fetch_assoc();
                      echo $role_row['role_name']; ?>
                    </td>
                    <td><?php echo $row['mno']; ?></td>
                  </tr>
                <?php } ?>

                <?php while ($row_2 = $result_doc->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row_2['fname'] . " " . $row_2['lname']; ?></td>
                    <td>Doctor</td>
                    <td><?php echo $row_2['contact_no']; ?></td>
                  </tr>
                <?php } ?>

                <?php while ($row_3 = $result_nurse->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row_3['fname'] . " " . $row_3['lname']; ?></td>
                    <td>Nurse</td>
                    <td><?php echo $row_3['contact_no']; ?></td>
                  </tr>
                <?php } ?>

                <?php while ($row_4 = $result_mem->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row_4['fname'] . " " . $row_4['lname']; ?></td>
                    <td>Other Staff Member</td>
                    <td><?php echo $row_4['contact_no']; ?></td>
                  </tr>
                <?php } ?>

              </table>
              <hr>
              <center>
                <h6 style="color:#FF8484;">(Please don't share these contact numbers with external peoples)</h6>
              </center>
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
      /////////////////////////// Enable data tables plugin ///////////////////////////
      $('#mng_contactTB').DataTable();
    });
  </script>
</body>

</html>