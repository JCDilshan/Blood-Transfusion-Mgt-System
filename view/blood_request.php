<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 2) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Blood Request</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <style type="text/css">
    label.bg-dark {
      color: #FFF;
    }

    input[disabled] {
      background-color: #3C0;
    }
  </style>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">

    <?php include('../includes/nav_bar.php'); ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>
      <!--Content Start-->

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <div class="row mt-2">

        <div class="col-md-10 offset-1">

          <div class="card">
            <div class="card-header text-center bg-dark">
              <span style="font-size:28px; text-transform:uppercase; color:#FFF; letter-spacing:5px;"><b>Blood Request Form</b></span>
            </div>
            <div class="card-body">
              <form id="blood_request" action="../controller/patient_controller.php?status=add_request" method="post">

                <div class="row">

                  <div class="col-md-10">
                    <label class="bg-danger p-1" style="color:#FFF;">Request Type :-</label> &nbsp;
                    <span class="text-success" style="font-size:18px;"><b>Routing </b></span><input value="3" type="radio" name="reqtype"> &nbsp;&nbsp;
                    <span class="text-warning" style="font-size:18px;"><b>Urgent </b></span><input value="2" type="radio" name="reqtype"> &nbsp;&nbsp;
                    <span class="text-danger" style="font-size:18px;"><b>Emergency </b></span><input value="1" type="radio" name="reqtype">
                    <!--<span><b>(Refer End of the page for Category Description)</b></span>-->
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-12 text-justify">
                    <table border="0" align="center" style="width:inherit;">
                      <tr>
                        <td colspan="3">
                          <h5>1. Identification Details</h5>
                        </td>
                      </tr>
                      <tr>
                        <td><label>1.1 N.I.C No :</label></td>
                        <td colspan="3"><input id="nic" name="nic" type="text" required autocomplete="off"></td>
                      </tr>
                      <tr>
                        <td><label>1.2 Patient's Name :</label></td>
                        <td colspan="3"><input id="pat_name" name="pat_name" type="text" style="width:75%;" required autocomplete="off"></td>
                      </tr>
                      <tr>
                        <td><label>1.3 Age :</label></td>
                        <td><input id="age" name="pat_age" type="number" required autocomplete="off"></td>
                        <td><label>1.4 Sex :</label></td>
                        <td>Male <input id="M" value="M" type="radio" name="gender"> Female <input id="F" value="F" type="radio" name="gender"></td>
                      </tr>
                      <tr>
                        <td><label>1.5 Weight(Kg) :</label></td>
                        <td colspan="3"><input id="weight" name="weight" type="number" required autocomplete="off"></td>
                      </tr>
                      <tr>
                        <td><label>1.6 BHT :</label></td>
                        <td><input id="bht" name="bht" type="number" required autocomplete="off"></td>
                        <td><label>1.7 Ward :</label></td>
                        <td><input id="ward" name="ward" type="text" required autocomplete="off"></td>
                      </tr>
                      <tr style="border-bottom:thin #999 solid;">
                        <td><label>1.8 Hospital :</label></td>
                        <td colspan="3"><input name="hosp" type="text" style="width:75%;" required autocomplete="off"></td>
                      </tr>

                      <tr>
                        <td>
                          <h5>2. Blood Group</h5>
                        </td>
                      </tr>
                      <tr style="border-bottom:thin #999 solid;">
                        <td><label>2.1 Patient's Blood Group :</label></td>
                        <td>
                          <select id="blood_grp" name="blood_grp">
                            <option value="0">---</option>
                            <?php
                            include_once('../model/blood_model.php');
                            $grp_obj = new Blood_Grp($conn);
                            $result = $grp_obj->give_Allgroups();
                            while ($row = $result->fetch_assoc()) { ?>
                              <option value="<?php echo $row['grp_id']; ?>"><?php echo $row['grp_name']; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td><label>2.2 If &lt; 4 Months, Mother's Group :</label></td>
                        <td>
                          <select name="mblood_grp">
                            <option value="0">---</option>
                            <?php
                            include_once('../model/blood_model.php');
                            $grp_obj = new Blood_Grp($conn);
                            $result = $grp_obj->give_Allgroups();
                            while ($row = $result->fetch_assoc()) { ?>
                              <option value="<?php echo $row['grp_id']; ?>"><?php echo $row['grp_name']; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>

                      <tr style="border-bottom:thin #999 solid;">
                        <td>
                          <h5 class="text-left">3. Diagnosis / Clinical Condition :</h5>
                        </td>
                        <td class="p-1" colspan="3"><textarea name="diagnosis" type="text" style="width:80%;" required autocomplete="off"></textarea>
                      </tr>

                      <tr>
                        <td>
                          <h5>4. Transfusion History :</h5>
                        </td>
                        <td colspan="3"><span>Yes</span> <input value="Yes" type="radio" name="TH"> <span>No</span> <input value="No" type="radio" name="TH"></td>
                      </tr>
                      <tr id="trans_yes">
                        <td><label>4.1 If Yes, When ? :</label></td>
                        <td><span>Within Last 3 Months</span> <input value="Within Last 3 Months" type="radio" name="when"><br> <span>Before 3 Months</span> <input value="Before 3 Months" type="radio" name="when"></td>
                        <td><label>4.2 Any Reactions :</label></td>
                        <td><span>Yes</span> <input value="Yes" type="radio" name="AR"> <span>No</span> <input value="No" type="radio" name="AR"></td>
                      </tr>
                      <tr id="any_react">
                        <td><label>4.3 If Yes, What were the Symptoms / Sings ?</label></td>
                        <td colspan="3"><textarea id="symptom" name="react_symptom"></textarea></td>
                      </tr>

                      <tr style="border-top:thin #999 solid;">
                        <td colspan="2">
                          <h5>5. Current Indication for Transfusion :</h5>
                        </td>
                        <td colspan="2"></td>
                      </tr>
                      <tr style="border-bottom:thin #999 solid;">
                        <td><label>5.1 Indicate <b>Hb Level</b> :</label></td>
                        <td><input name="hb_level" type="text" required autocomplete="off"></td>
                        <td><label>5.2 Tested Date</label></td>
                        <td><input name="hbTest_date" id="hbTest_date" type="date" required></td>
                      </tr>

                      <tr>
                        <td colspan="2">
                          <h5>6. For Blood Reservation for Sergeries :</h5>
                        </td>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr style="border-bottom:thin #999 solid;">
                        <td><label>6.1 Indicate the Sergery / Procedure :</label></td>
                        <td><input name="indic_procedure" value="" type="text" autocomplete="off"></td>
                        <td><label>6.2 Date :</label></td>
                        <td><input name="indic_date" value="" type="date"></td>
                      </tr>

                      <tr style="border-bottom:thin #999 solid;">
                        <td colspan="2">
                          <h5>7. Amount Of Blood Required :</h5>
                        </td>
                        <td colspan="2"><input id="req_amount" name="req_amount" type="number" placeholder="number of Bags" autocomplete="off" required></td>
                      </tr>

                      <tr>
                        <td colspan="2"><label>Filler's Username :</label>&nbsp;<input id="username" type="text" autocomplete="off"></td><input id="user_id" type="hidden" name="user_id">
                        <td colspan="2"><label>Password :</label>&nbsp;<input id="pw" type="password"></td><br>
                      </tr>

                    </table>
                    <label>Checked User:</label> <kbd id="userChecked" style="color:#F00; background-color:#333;">Not Checked</kbd>
                  </div>

                </div>

                <button id="submit" class="btn btn-success" type="submit" disabled>Submit</button>

              </form>
            </div>
          </div>

        </div>

      </div>

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/validations/bloodReq_val.js"></script>

</body>

</html>