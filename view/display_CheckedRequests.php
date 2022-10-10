<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 4) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$request_id = isset($_REQUEST['request_id']) ? base64_decode($_REQUEST['request_id']) : '';
$checkStatus = isset($_REQUEST['checkStatus']) ? base64_decode($_REQUEST['checkStatus']) : '';

include('../model/patient_model.php');
$bloodReq_obj = new Blood_Request($conn);
$result = $bloodReq_obj->get_SpecificRequests($request_id);
$row = $result->fetch_assoc();

include_once('../model/user_model.php');
$user_object = new User($conn);
$result_user = $user_object->give_info($row['filler_user_id']);
$row_user = $result_user->fetch_assoc();

include_once('../model/role_model.php');
$role_object = new Role($conn);
$get_role = $role_object->get_specificRole($row_user['user_role']);
$role_row = $get_role->fetch_assoc();

if ($checkStatus == "accept") {
  $result_acc = $bloodReq_obj->get_SpecificApprovedRequest($request_id);
  $row_acc = $result_acc->fetch_assoc();

  $result_user2 = $user_object->give_info($row_acc['checker_user_id']);
  $row_user2 = $result_user2->fetch_assoc();

  $get_role2 = $role_object->get_specificRole($row_user2['user_role']);
  $role_row2 = $get_role2->fetch_assoc();
} else if ($checkStatus == "reject") {
  $result_rej = $bloodReq_obj->get_SpecificRejectedRequest($request_id);
  $row_rej = $result_rej->fetch_assoc();

  $result_user2 = $user_object->give_info($row_rej['checker_user_id']);
  $row_user2 = $result_user2->fetch_assoc();

  $get_role2 = $role_object->get_specificRole($row_user2['user_role']);
  $role_row2 = $get_role2->fetch_assoc();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>View Form</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">

    <?php include('../includes/nav_bar.php'); ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>
      <!--Content Start-->

      <div class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark text-white">
              <span style="font-size:28px; text-transform:uppercase; letter-spacing:5px;"><b>Blood Request Form</b></span>
            </div>
            <div class="card-body">

              <div class="row">

                <div class="col-md-12 text-justify">
                  <label>Request ID :</label>
                  <h6 style="display:inline;" id="formId"><?php echo $row['request_id']; ?></h6> &nbsp;&nbsp;
                  <label>Priority :</label>
                  <h6 style="display:inline;"><?php if ($row['priority'] == 1) {
                                                echo "Emergency";
                                              } else if ($row['priority'] == 1) {
                                                echo "Urgent";
                                              } else {
                                                echo "Routing";
                                              } ?></h6>
                  <hr>

                  <table border="0" align="center" style="width:inherit; color:#000;">
                    <tr>
                      <td colspan="3">
                        <h5>1. Patient's Identification Details</h5>
                      </td>
                    </tr>
                    <tr>
                      <td><label>1.1 Patient's Name (Should match with BHT) :</label></td>
                      <td colspan="3"><?php echo $row['name']; ?></td>
                    </tr>
                    <tr>
                      <td><label>1.2 Age :</label></td>
                      <td><?php echo $row['age']; ?></td>
                      <td><label>1.3 Sex :</label></td>
                      <td><?php if ($row['gender'] == "M") {
                            echo "Male";
                          } else {
                            echo "Female";
                          } ?></td>
                    </tr>
                    <tr>
                      <td><label>1.4 Weight :</label></td>
                      <td><?php echo $row['weight'] . " Kg"; ?></td>
                      <td><label>1.5 N.I.C. :</label></td>
                      <td><?php echo $row['nic_no']; ?></td>
                    </tr>
                    <tr>
                      <td><label>1.6 BHT :</label></td>
                      <td><?php echo $row['bht']; ?></td>
                      <td><label>1.7 Ward :</label></td>
                      <td><?php echo $row['ward']; ?></td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>1.8 Hospital :</label></td>
                      <td colspan="3"><?php echo $row['hospital']; ?></td>
                    </tr>

                    <tr>
                      <td>
                        <h5>2. Blood Group</h5>
                      </td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>2.1 Patient's Blood Group :</label></td>
                      <td><?php echo $row['blood_grp']; ?></td>
                      <td><label>2.2 Mother's Blood Group :</label></td>
                      <td><?php echo $row['mblood_grp']; ?></td>
                    </tr>

                    <tr style="border-bottom:thin #999 solid;">
                      <td>
                        <h5>3. Diagnosis / Clinical Condition :</h5>
                      </td>
                      <td colspan="3"><?php echo $row['diagnosis']; ?></td>
                    </tr>

                    <tr>
                      <td>
                        <h5>4. Transfusion History :</h5>
                      </td>
                      <td colspan="3"><?php echo $row['trans_history']; ?></td>
                    </tr>
                    <tr>
                      <td><label>4.1 If Yes, When ? :</label></td>
                      <td colspan="3"><?php echo $row['trans_when']; ?></td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>4.2 What were the Symptoms / Sings ?</label></td>
                      <td colspan="3"><?php echo $row['react_symptom']; ?></td>
                    </tr>

                    <tr>
                      <td>
                        <h5>5. Current Indication for Transfusion :</h5>
                      </td>
                      <td colspan="3"></td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>5.1 Indicate <b>Hb Level</b> :</label></td>
                      <td><?php echo $row['hb_level']; ?></td>
                      <td><label>5.2 Tested Date</label></td>
                      <td><?php echo $row['hb_tested_date']; ?></td>
                    </tr>

                    <tr>
                      <td colspan="2">
                        <h5>6. For Blood Reservation for Sergeries :</h5>
                      </td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>6.1 Indicate the Sergery / Precedure :</label></td>
                      <td><?php echo $row['indicate_procedure']; ?></td>
                      <td><label>6.2 Date :</label></td>
                      <td><?php echo $row['indicate_date']; ?></td>
                    </tr>

                    <tr style="border-bottom:thin #999 solid;">
                      <td>
                        <h5>7. Amount Of Blood Required(Bags) :</h5>
                      </td>
                      <td colspan="3"></td>
                    </tr>
                    <hr>
                    <tr>
                      <td><label>Request Filler Name :</label></td>
                      <td colspan="3"><?php echo $row_user['fname'] . " " . $row_user['lname'] . " - " . $row_user['user_id'] . " (" . $role_row['role_name'] . ")"; ?></td>
                    </tr>

                  </table>

                </div>

              </div>
              <!--1st Row End-->

              <hr style="background-color:#F00; height:2px;">


              <div class="row">

                <div class="col-md-12">

                  <table border="0">

                    <tr>
                      <td colspan="2">
                        <h5><label>Status :</label></h5>
                      </td>
                      <td colspan="2">
                        <?php if ($checkStatus == "accept") {
                          echo "<h5 style='color:#0C0;'>Accepted</h5>";
                        } else {
                          echo "<h5 style='color:#F00;'>Rejected</h5>";
                        } ?>
                      </td>
                    </tr>

                    <tr>
                      <td colspan="2"><label>Checked Date / Time :</label></td>
                      <td colspan="2">
                        <h5>&nbsp; <?php if ($checkStatus == "accept") {
                                      echo date("jS M Y g:i A", strtotime($row_acc['checked_date']));
                                    } else {
                                      echo date("jS M Y g:i A", strtotime($row_rej['checked_date']));
                                    } ?></h5>
                      </td>
                    </tr>

                    <tr>
                      <td><label>Request Checker Name :</label></td>
                      <td colspan="3">
                        <h5>&nbsp; <?php echo $row_user2['fname'] . " " . $row_user2['lname'] . " - " . $row_user2['user_id'] . " (" . $role_row2['role_name'] . ")"; ?></h5>
                      </td>
                    </tr>

                  </table>

                </div>

              </div>

              <div>&nbsp;</div>

              <?php if ($checkStatus == "accept") { ?>
                <!--Approval Part Start-->
                <div class="row">

                  <div class="col-md-12">

                    <h6 style="color:#666;">Blood Grouping (Graded Results) :</h6>

                    <table id="Bgrouping" class="table table-bordered table-striped text-center">
                      <tr style="background-color:#333; color:#FFF;">
                        <td>Anti A</td>
                        <td>Anti AB</td>
                        <td>Anti B</td>
                        <td>Anti D</td>
                        <td>&nbsp;</td>
                        <td>A Cell</td>
                        <td>B cell</td>
                        <td>O Cell</td>
                        <td>Blood Group</td>
                      </tr>
                      <tr>
                        <td><?php echo $row_acc['anti_a']; ?></td>
                        <td><?php echo $row_acc['anti_ab']; ?></td>
                        <td><?php echo $row_acc['anti_b']; ?></td>
                        <td><?php echo $row_acc['anti_d']; ?></td>
                        <td></td>
                        <td><?php echo $row_acc['cell_a']; ?></td>
                        <td><?php echo $row_acc['cell_b']; ?></td>
                        <td><?php echo $row_acc['cell_o']; ?></td>
                        <td><?php echo $row_acc['grp']; ?></td>
                      </tr>
                    </table>

                    <h6 style="color:#666;">Anti Body Screen (Graded Results) :</h6>

                    <table id="Bgrouping" class="table table-bordered table-striped text-center">
                      <tr style="background-color:#333; color:#FFF;">
                        <td></td>
                        <td>37<sup>0</sup>C</td>
                        <td>IAT</td>
                      </tr>
                      <tr>
                        <td>Screen Cells - S1</td>
                        <td><?php echo $row_acc['s1_37']; ?></td>
                        <td><?php echo $row_acc['s1_iat']; ?></td>
                      </tr>
                      <tr style="border-bottom:#666 medium double;">
                        <td>Screen Cells - S2</td>
                        <td><?php echo $row_acc['s2_37']; ?></td>
                        <td><?php echo $row_acc['s2_iat']; ?></td>
                      </tr>
                      <tr>
                        <td>Component Type</td>
                        <td colspan="2">
                          <?php
                          include_once("../model/blood_model.php");
                          $bloodComp_obj = new Blood_Component($conn);
                          $getSpec_comp = $bloodComp_obj->give_SpecificComp($row['comp_type']);
                          $getSpec_compArray = $getSpec_comp->fetch_assoc();

                          echo $getSpec_compArray["comp_name"];
                          ?>
                        </td>
                      </tr>
                    </table>

                  </div>

                </div>
                <!--Approval Part End-->
              <?php } else if ($checkStatus == "reject") { ?>
                <div class="row">

                  <div class="col-md-6">
                    <p style="color:#000;"><span style="font-size:18px;"><b>Rejected Reason :- </b></span><?php echo $row_rej['rej_reason']; ?></p>
                  </div>

                </div>
              <?php } ?>

              <button onclick="window.print();" class="btn btn-danger no-print">
                <i class="fa fa-print"></i> Print Form
              </button>

            </div>
          </div>

        </div>

      </div>

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/pendReq_val.js"></script>
</body>

</html>