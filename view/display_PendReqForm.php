<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 4) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$request_id = isset($_REQUEST['request_id']) ? base64_decode($_REQUEST['request_id']) : '';

include('../model/patient_model.php');
$bloodReq_obj = new Blood_Request($conn);
$result = $bloodReq_obj->get_SpecificRequests($request_id);
$row = $result->fetch_assoc();

include('../model/user_model.php');
$user_object = new User($conn);
$result_2 = $user_object->give_info($row['filler_user_id']);
$row_2 = $result_2->fetch_assoc();

include('../model/blood_model.php');
$grp_object = new Blood_Grp($conn);
$result_3 = $grp_object->give_Allgroups();

$singleBlood_result_1 = $grp_object->give_SpecificGroup($row["blood_grp"]);
$singleBlood_row_1 = $singleBlood_result_1->fetch_assoc();

if (isset($row["mblood_grp"])) {
  $singleBlood_result_2 = $grp_object->give_SpecificGroup($row["mblood_grp"]);
  $singleBlood_row_2 = $singleBlood_result_2->fetch_assoc();
} else {
  $singleBlood_row_2["grp_name"] = "";
}

$comp_object = new Blood_Component($conn);
$result_4 = $comp_object->give_Allcomponents();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>View Request</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <style type="text/css">
    #Bgrouping1 input {
      width: 60px;
      height: 30px;
    }

    #Bgrouping1 tr>td {
      color: #FFF !important;
    }

    #Bgrouping2 tr>td {
      color: #000;
    }

    #CMP input {
      width: 80px;
    }

    td {
      color: #000;
    }
  </style>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">

    <?php include('../includes/nav_bar.php'); ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>
      <!--Content Start-->

      <div class="row mt-2">

        <div class="col-md-9 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <span style="font-size:28px; color:#FFF; text-transform:uppercase; letter-spacing:5px;"><b>Blood Request Form</b></span>
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

                  <table class="table table-bordered" style="width:100%;">
                    <tr>
                      <td colspan="4">
                        <h5>1. Identification Details</h5>
                      </td>
                    </tr>
                    <tr>
                      <td><label>1.1 Patient's Name :</label></td>
                      <td colspan="3"><?php echo $row['name']; ?></td>
                    </tr>
                    <tr>
                      <td style="width:35%;"><label>1.2 Age :</label></td>
                      <td><?php echo $row['age']; ?></td>
                      <td style="width:26%;"><label>1.3 Sex :</label></td>
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
                      <td colspan="4">
                        <h5>2. Blood Group</h5>
                      </td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>2.1 Patient's Blood Group :</label></td>
                      <td><?php echo $singleBlood_row_1["grp_name"]; ?></td>
                      <td><label>2.2 Mother's Blood Group :</label></td>
                      <td><?php echo $singleBlood_row_2["grp_name"] ?></td>
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
                    <tr>
                      <td><label>4.2 What were the Symptoms / Sings ?</label></td>
                      <td colspan="3"><?php echo $row['react_symptom']; ?></td>
                    </tr>

                    <tr style="border-top:thin #999 solid;">
                      <td colspan="4">
                        <h5>5. Current Indication for Transfusion :</h5>
                      </td>
                    </tr>
                    <tr style="border-bottom:thin #999 solid;">
                      <td><label>5.1 Indicate <b>Hb Level</b> :</label></td>
                      <td><?php echo $row['hb_level']; ?></td>
                      <td><label>5.2 Tested Date</label></td>
                      <td><?php echo $row['hb_tested_date']; ?></td>
                    </tr>

                    <tr>
                      <td colspan="4">
                        <h5>6. For Blood Reservation for Sergeries :</h5>
                      </td>
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
                      <td colspan="3">
                        <input type="hidden" value="<?php echo $row['request_id']; ?>" id="req_id">
                        <input value="<?php echo $row['require_amount']; ?>" type="number" id="edit_amount" readonly>
                        <button id="edit_amountBtn" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit Amount</button>
                        <button id="set_amountBtn" class="btn btn-success btn-sm" hidden>Set New Amount</button>
                        <h5 id="edit_amountRes" style="color:#00C;"></h5>
                      </td>
                    </tr>
                    <hr>
                    <td><label>Request Filler Name :</label></td>
                    <td colspan="3"><?php echo $row_2['fname'] . " " . $row_2['lname'] . " (User ID - " . $row_2['user_id'] . ")"; ?></td>
                    </tr>

                  </table>

                </div>

              </div>
              <!--1st Row End-->

              <hr style="background-color:#F00; height:2px;">


              <div class="row">

                <div class="col-md-12">

                  <h5 id="acceptancePart" style="color:#F00;"><u>REQUEST ACCEPTANCE PART (For Medical Officer*)</u></h5>

                  <table border="0">

                    <tr>
                      <td colspan="2"><label>Recieved Date / Time :</label></td>
                      <td colspan="2">
                        <h5><?php echo $row['added']; ?></h5>
                      </td>
                    </tr>

                    <tr>
                      <td colspan="2">
                        <h5 style="color:#FFF; background-color:#900;">Request & Sample Check :</h5>
                      </td>
                      <td colspan="2"><span style="color:#000; padding-left:15px; font-size:24px;">Acceptable</span>&nbsp;&nbsp;<input value="acc" name="stmt" type="radio"> &nbsp;&nbsp;
                        <span style="color:#000; font-size:24px;">Unacceptable</span>&nbsp;&nbsp;<input name="stmt" value="unacc" type="radio">
                      </td>
                    </tr>

                  </table>

                </div>

              </div>

              <hr>

              <div class="row" id="unacceptable">
                <form id="pendReqRej" action="../controller/patient_controller.php?status=req_reject&req_id=<?php echo $row['request_id']; ?>" method="post">
                  <input name="user_id" type="hidden">
                  <div class="col-md-12">
                    <label>Remarks <b>(unacceptable reason)</b> :</label>
                    <div class="form-group">
                      <textarea id="unacc_reason" name="req_reason" class="form-control" required></textarea>
                    </div>

                    <button name="submit" type="submit" class="btn btn-success" disabled>Finish <i class="fa fa-check"></i></button>
                  </div>
                </form>
              </div>
              <!--Reject Part End-->

              <!--Approval Part Start-->
              <div class="row" id="acceptable">

                <div class="col-md-12">

                  <h6 style="color:#666;">Blood Grouping (Grade Your Results) :</h6>

                  <form id="pendReqAcc" action="../controller/patient_controller.php?status=req_approval&req_id=<?php echo $row['request_id']; ?>" method="post">
                    <input name="user_id" type="hidden">
                    <table id="Bgrouping1" class="table table-bordered table-striped text-center">
                      <tr style="background-color:#000;">
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
                        <td><input id="anti_A" name="anti_a" type="number" required></td>
                        <td><input name="anti_ab" type="number" min="0" required></td>
                        <td><input name="anti_b" type="number" min="0" required></td>
                        <td><input name="anti_d" type="number" min="0" required></td>
                        <td></td>
                        <td><input name="cell_a" type="number" min="0" required></td>
                        <td><input name="cell_b" type="number" min="0" required></td>
                        <td><input name="cell_o" type="number" min="0" required></td>
                        <td>
                          <select name="blood_grp" required>
                            <option>---</option>
                            <?php while ($row_3 = $result_3->fetch_assoc()) { ?>
                              <option value="<?php echo $row_3['grp_id']; ?>"><?php echo $row_3['grp_name']; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>
                    </table>

                    <h6 style="color:#666;">Anti Body Screen (Grade Your Results) :</h6>

                    <table id="Bgrouping2" class="table table-bordered table-striped text-center">
                      <tr style="background-color:#000;">
                        <td></td>
                        <td style="color:#FFF;">37<sup>0</sup>C</td>
                        <td style="color:#FFF;">IAT</td>
                      </tr>
                      <tr>
                        <td>Screen Cells - S1</td>
                        <td><input name="s1_37" value="yes" type="checkbox"></td>
                        <td><input name="s1_iat" value="yes" type="checkbox"></td>
                      </tr>
                      <tr>
                        <td>Screen Cells - S2</td>
                        <td><input name="s2_37" value="yes" type="checkbox"></td>
                        <td><input name="s2_iat" value="yes" type="checkbox"></td>
                      </tr>
                      <tr>
                        <td>Blood Component Type</td>
                        <td colspan="2">
                          <select name="comp_type" class="form-control" required style="width:60%;">
                            <option value="">---</option>
                            <?php while ($row_4 = $result_4->fetch_assoc()) { ?>
                              <option value="<?php echo $row_4['comp_id']; ?>"><?php echo $row_4['comp_name']; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>
                    </table>

                    <label>Filler's Username :</label>
                    <input id="username" type="text" autocomplete="off">&nbsp;
                    <label>Password :</label>
                    <input type="password" id="pw"><br>
                    <label>Check User :- </label>
                    <kbd id="userChecked" style="color:#F00; background-color:#333;">Not Checked</kbd>

                    <hr>

                    <button name="submit" type="submit" class="btn btn-success" disabled>Finish <i class="fa fa-check"></i></button>

                  </form>

                </div>

              </div>
              <!--Approval Part End-->

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