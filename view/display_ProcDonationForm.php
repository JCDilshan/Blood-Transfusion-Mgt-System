<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 5) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

$donation_id = base64_decode($_REQUEST['donation_id']);

include('../model/donation_model.php');
$donation_obj = new Donation($conn);
$result = $donation_obj->get_SpecificDonation($donation_id);
$row = $result->fetch_assoc();

include('../model/donor_model.php');
$donor_obj = new Donor($conn);
$result_2 = $donor_obj->get_SpecificDonor($row['donor_id']);
$row_2 = $result_2->fetch_assoc();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Test Proceed</title>

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

      <!--Row Start-->
      <div class="row pl-2">

        <div class="col-md-8 offset-2">

          <form id="CheckResultForm" action="../controller/donation_controller.php?status=checked_result" method="post">

            <table class="table table-bordered table-striped text-justify">

              <tr style="background-color:#000;">
                <td colspan="2">
                  <h4 style="color:#FFF;">Donor ID :- <?php echo $row['donor_id']; ?> <br> <?php if ($row['camp_id'] != NULL) {
                                                                                              echo "Camp ID :- " . $row['camp_id'];
                                                                                            } else {
                                                                                              echo "Hospital ID :- " . $row['hospital_id'];
                                                                                            } ?></h4>
                </td>
              </tr>

              <input type="hidden" value="<?php echo $donation_id; ?>" name="donation_id">
              <input type="hidden" value="<?php echo $row['bag_id']; ?>" name="bag_id">
              <input type="hidden" value="<?php echo $row['donor_id']; ?>" name="donor_id">
              <input type="hidden" value="<?php echo $row['camp_id']; ?>" name="camp_id">
              <input type="hidden" value="<?php echo $row['hospital_id']; ?>" name="hosp_id">

              <tr>
                <td>Bag ID :</td>
                <td><?php echo $row['bag_id']; ?></td>
              </tr>
              <tr>
                <td>Donor Name :</td>
                <td><?php echo $row_2['donor_name']; ?></td>
              </tr>
              <td>Blood Group :</td>
              <td><?php echo $row_2['grp_name']; ?></td>
              </tr>
              <tr>
                <td>Blood Donated Date</td>
                <td><?php echo $row['donated_date']; ?></td>
              </tr>
              <tr>
                <td>Blood Checked Date</td>
                <td><input class="form-control" id="check_date" type="date" name="check_date" required></td>
              </tr>

            </table>

            <button type="submit" id="accBtn" class="btn btn-success">Accept Blood <i class="fa fa-check"></i></button>
            <button type="button" id="rejectBtn" class="btn btn-danger">Reject Blood <i class="fa fa-times"></i></button>
            <input id="actionValue" type="hidden" value="1" name="action">
        </div>

      </div>

      <div>&nbsp;</div>

      <div class="row pl-2" id="rej_reason">

        <div class="col-md-8 offset-2">
          <div class="form-group">

            <label>
              <h5>Mention Briefly Rejected Reason :</h5>
            </label>
            <textarea id="rej_reasonTxt" class="form-control" name="rej_reason"></textarea>
            <br>

            <button type="submit" id="rejectBtn2" class="btn btn-danger"></button>

            </form>
          </div>
        </div>

      </div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script src="../js/displayProcDon.js"></script>

</body>

</html>