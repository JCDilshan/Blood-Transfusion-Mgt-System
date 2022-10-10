<?php
include_once('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include_once('../model/user_model.php');
$user_object = new User($conn);
$result_users = $user_object->give_Allusers();

include_once('../model/schedule_model.php');
$appoint_object = new Appointment($conn);
$result_appoint = $appoint_object->get_ListedAppointments();

include_once('../model/camp_model.php');
$camp_object = new Camp($conn);
$result_camps = $camp_object->getAllcamps();

include_once('../model/donor_model.php');
$donor_object = new Donor($conn);
$result_donors = $donor_object->get_Alldonors();

include_once('../model/staff_model.php');
$doctor_object = new Doctor($conn);
$nurse_object = new Nurse($conn);
$result_doctors = $doctor_object->get_Alldoctors();
$result_nurse = $nurse_object->get_AllNurse();

include_once('../model/patient_model.php');
$BloodReq_object = new Blood_Request($conn);
$result_req = $BloodReq_object->get_PendingRequests();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <title>Dashboard</title>

  <?php
  include_once('../includes/css_assets.php');
  include_once('../includes/js_assets.php');
  ?>
  <link href="../css/dashboard.css" rel="stylesheet">

</head>

<body>

  <div class="wrapper d-flex align-items-stretch">

    <?php include_once('../includes/nav_bar.php'); ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <!--Content Start-->

      <!--1st Row Start-->
      <div class="row">

        <div class="col-md-6 col-sm-8 col-xs-12 text-center">

          <div class="card text-center shadow h-100">

            <div class="card-header">
              <h4 style="color:#000; font-weight:bolder;"><b>Current Inventory</b></h4>
            </div>

            <div class="card-body row" id="fsvg_Row">

              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="percent" id="grp1">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>

                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- <div class="card">-->
                <div class="percent" id="grp2">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- <div class="card">-->
                <div class="percent" id="grp3">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!--<div class="card">-->
                <div class="percent" id="grp4">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <br>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!--<div class="card">-->
                <div class="percent" id="grp5">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!--<div class="card">-->
                <div class="percent" id="grp6">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- <div class="card">-->
                <div class="percent" id="grp7">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

              <div class="col-md-3 col-sm-6 col-xs-12">
                <!--<div class="card">-->
                <div class="percent" id="grp8">
                  <svg>
                    <circle cx="62" cy="70" r="48"></circle>
                    <circle cx="62" cy="70" r="48"></circle>
                    <text font-size="20" font-weight="bolder" x="52" y="85">0%</text>
                  </svg>
                </div>
                <h4 class="text"></h4>
                <!--</div>-->
              </div>

            </div>

          </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">

          <div class="row">

            <div class="col-md-6 col-sm-12 col-xs-12">

              <div class="card shadow">
                <div class="card-body" style="max-height:100%;">
                  <canvas id="myChart3"></canvas>
                </div>
              </div>

            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">

              <div class="card shadow" style="min-height: 100%;">
                <div class="card-header text-center" style="background:linear-gradient(to right, #060, #090);">
                  <h6 style="color:#FFF;"><i class="fa fa-users"></i> Active Users</h6>
                </div>
                <div class="card-body p-1 pt-2" style="overflow:auto; max-width:100%">
                  <table cellspacing="0" style="width:100%; border:thin; border-collapse: collapse;">
                    <?php while ($row = $result_users->fetch_assoc()) {
                      if ($row['login_status'] == 1) { ?>
                        <tr style="border-bottom:#CCC thin solid;" class="p-2">
                          <?php
                          include_once('../model/role_model.php');
                          $role_object = new Role($conn);
                          $result_role = $role_object->get_specificRole($row['user_role']);
                          $row_role = $result_role->fetch_assoc();
                          ?>
                          <td style="width:24%;">
                            <img src="../images/users/<?php echo $row['user_img'] ?>" width="35" height="35" style="border-radius:50%;">
                            <i id="online_icon" class="fa fa-circle"></i>
                          </td>
                          <td style="font-size:11px; color:#000; font-weight:bolder;">
                            <?php echo " " . $row['fname'] . " " . $row['lname'] . "<br> (" . $row_role['role_name'] . ")"; ?>
                          </td>
                        </tr>
                    <?php }
                    } ?>
                  </table>
                </div>
              </div>

            </div>

          </div>

          <div class="row innerListRow mt-3">

            <div class="col-md-4 col-sm-2 col-xs-6">
              <div class="alert-dark text-center shadow pt-2">
                <i class="fa fa-user fa-2x"></i>
                <h6 class="fa-2x"><?php echo $result_appoint->num_rows; ?></h6>
                <span style="font-size:15px;">Listed Appointments</span>
                <hr>
                <a href="mng_appointments.php" class="btn btn-dark btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>

            <div class="col-md-4 col-sm-2 col-xs-6">
              <div class="alert-dark text-center shadow pt-2">
                <i class="fa fa-campground fa-2x"></i>
                <h6 class="fa-2x"><?php echo $result_camps->num_rows; ?></h6>
                <span style="font-size:15px;">Listed Blood Camps</span>
                <hr>
                <a href="mng_camps.php" class="btn btn-dark btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>

            <div class="col-md-4 col-sm-2 col-xs-6">
              <div class="alert-dark text-center shadow pt-2">
                <i class="fa fa-user fa-2x"></i>
                <h6 class="fa-2x"><?php echo $result_req->num_rows; ?></h6>
                <span style="font-size:15px;">Blood Requests</span>
                <hr>
                <a href="pending_blood_request.php" class="btn btn-dark btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
              </div>
            </div>

          </div>


        </div>

      </div>
      <!--1st Row End-->

      <!--2nd Row Start-->
      <div class="row innerListRow mt-5">

        <div class="col-md-3 col-sm-4 col-xs-8">
          <div class="alert alert-info text-center shadow">
            <i class="fa fa-users fa-3x"></i>
            <h6 class="fa-2x"><?php echo $result_users->num_rows; ?></h6>
            <span style="font-size:20px;">Registered Users</span>
            <hr>
            <a href="mng_user.php" class="btn btn-info btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-8">
          <div class="alert alert-info text-center shadow">
            <i class="fa fa-users fa-3x"></i>
            <h6 class="fa-2x"><?php echo $result_donors->num_rows; ?></h6>
            <span style="font-size:20px;">Registered Donors</span>
            <hr>
            <a href="mng_donors.php" class="btn btn-info btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-8">
          <div class="alert alert-info text-center shadow">
            <i class="fa fa-user-tie fa-3x"></i>
            <h6 class="fa-2x"><?php echo $result_doctors->num_rows; ?></h6>
            <span style="font-size:20px;">Total Docotors</span>
            <hr>
            <a href="doctors.php" class="btn btn-info btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-8">
          <div class="alert alert-info text-center shadow">
            <i class="fa fa-user-nurse fa-3x"></i>
            <h6 class="fa-2x"><?php echo $result_nurse->num_rows; ?></h6>
            <span style="font-size:20px;">Total Nurse</span>
            <hr>
            <a href="nurse.php" class="btn btn-info btn-sm">View Details <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>

      </div>
      <!--2nd Row End-->

      <!--3rd Row Start-->
      <div id="tRow" class="row mt-2">

        <div class="col-md-12 col-sm-6 col-xs-12">

          <div class="card shadow">
            <div class="card-header text-center bg-dark text-white">
              <span style="font-size:24px; letter-spacing:5px;"><b>Transfusion History</b></span>
            </div>
            <div class="card-body">

              <div class="row">

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <canvas id="myChart"></canvas>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  <canvas id="myChart2"></canvas>
                </div>

              </div>

            </div>
          </div>

        </div>

      </div>
      <!--3rd Row End-->

    </div>

  </div>
  </div>


  <script src="../js/mycharts.js"></script>
  <script src="../js/dashboard.js"></script>
</body>

</html>