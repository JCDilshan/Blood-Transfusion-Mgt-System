<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 3) {
  header("HTTP/1.1 400 Unauthorized access");
  die("Access Denied");
}

include('../model/inventory_model.php');
$inventory_obj = new Inventory($conn);
$result = $inventory_obj->get_AllDetails();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Manage Inventory</title>

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
  <!-- ///////////////////////// Content wrapper start ///////////////////////// -->
  <div class="wrapper d-flex align-items-stretch">

    <?php
    include('../includes/nav_bar.php');
    ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <!-- ///////////////////////// Response alert ///////////////////////// -->
      <?php include_once("../includes/res_alert.php"); ?>

      <!--2nd Row Start-->
      <div class="row">

        <div class="col-md-12">

          <div class="card">
            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <span style="font-size:28px; letter-spacing:5px;"><b>Blood Inventory</b></span>
            </div>
            <div class="card-body bg-light">

              <button id="quick_blockBtn" class="btn btn-danger btn-sm">Emergency Block Here <i class="fa fa-arrow-down"></i></button>

              <form id="quick_block" action="../controller/inventory_controller.php?status=quick_block" method="post">
                <hr>
                <div class="row ml-1">

                  <div class="form-group" style="width:25%;">
                    <label class="alert-danger" style="font-weight:bolder;">Block By :</label>
                    <select id="BlockBy" name="category" class="form-control">
                      <option value="">---</option>
                      <option value="donor_id">Donor ID</option>
                      <option value="camp_id">Camp ID</option>
                      <option value="hospital_id">Hospital ID</option>
                      <option value="added_date">Added Date</option>
                    </select>
                  </div>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <div class="form-group" style="width:25%;">
                    <label class="alert-danger" style="font-weight:bolder;">Target :</label>
                    <input id="target" class="form-control" value="" name="target" autocomplete="off">
                  </div>
                  &nbsp;&nbsp;&nbsp;
                  <div class="form-group" style="width:20%; padding-top:36px;">
                    <button id="QuickBlockBtn" type="submit" class="btn btn-danger btn-sm">Block <i class="fa fa-lock"></i></button>
                  </div>

                </div>
              </form>

              <hr>

              <table class="table table-bordered text-center" id="mng_invenTB" style="width:100%;">
                <thead class="tbhead">
                  <tr>
                    <td>#Bag ID</td>
                    <td>Blood Group</td>
                    <td style="width:18%;">Component Type</td>
                    <td style="width:14%;">Added Date</td>
                    <td style="width:14%;">Expire Date</td>
                    <td>#Donor ID</td>
                    <td>#Camp ID</td>
                    <td>#Hospital ID</td>
                    <td style="width:42%;">Action</td>
                  </tr>
                </thead>

                <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['bag_id']; ?></td>
                    <td><?php echo $row['grp_name']; ?></td>
                    <td><?php echo $row['comp_name']; ?></td>
                    <td><?php echo $row['added_date']; ?></td>
                    <td><?php echo $row['expire_date']; ?></td>
                    <td><?php echo $row['donor_id']; ?></td>
                    <td><?php if ($row['camp_id'] != NULL) {
                          echo $row['camp_id'];
                        } else {
                          echo "-";
                        } ?></td>
                    <td><?php if ($row['hospital_id'] != NULL) {
                          echo $row['hospital_id'];
                        } else {
                          echo "-";
                        } ?></td>
                    <!--<td>
		/*if($row['block_status'] == 1){ echo "<span style='color:#0F0;'>Issuable</span>"; } 
		else if(($row['block_status'] == 0) && ($row['expire_date'] <= date("Y-m-d"))){ echo "<span style='color:#F00;'>Expired</span>"; }else { echo "<span style='color:#F00;'>Blocked</span>"; } */
		 
        </td>-->
                    <td>
                      <?php
                      if ($row['block_status'] == 1) { ?>
                        <a href="../controller/inventory_controller.php?status=active_blockByBag&bag_id=<?php echo $row['bag_id']; ?>&issue_stmt=1" class="btn btn-warning btn-sm BlockConf"><i class="fa fa-lock"></i> Block</a>
                      <?php
                      } else if ($row['block_status'] == 0) { ?>
                        <a href="../controller/inventory_controller.php?status=active_blockByBag&bag_id=<?php echo $row['bag_id']; ?>&issue_stmt=0" class="btn btn-success btn-sm"><i class="fa fa-unlock"></i> Un-Block</a>
                      <?php } ?>
                      <a href="../controller/inventory_controller.php?status=remove_bag&bag_id=<?php echo $row['bag_id']; ?>" class="btn btn-danger btn-sm RemoveConf"><i class="fa fa-times-circle"></i> Remove</a>
                    </td>
                  </tr>
                <?php } ?>
                <h5>(NOTE : Expired And Issued Blood Bags Will Automatically Hide From This Table.)</h5>
              </table>

              <!--<a href="#" class="btn btn-info">Inventory Monthly Records <i class="fa fa-arrow-right"></i></a>-->
            </div>
          </div>

        </div>

      </div>
      <!--Row End-->

      <div>&nbsp;</div>

    </div>

    <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script>
    $(document).ready(function(e) {

      ///////////////////////////// Enable data tables plugin ///////////////////////////
      $('#mng_invenTB').DataTable();

      ///////////////////////////// Quick block form hide as default ///////////////////////////
      $('#quick_block').hide();

      ///////////////////////////// Show quick block form when click quick block button ///////////////////////////
      $('#quick_blockBtn').click(function() {
        $('#quick_block').toggle('fast', function() {
          $('#BlockBy').focus();
        });
        $('#quick_blockBtn i').toggleClass('fa-arrow-down fa-arrow-up');
      });

      ///////////////////////////// Set target input field's input type ///////////////////////////
      $('#BlockBy').on('change', function() {

        category = $('#BlockBy').val();

        if ((category == "donor_id" || category == "camp_id") || category == "hospital_id") {
          $('#target').attr('type', 'number');
        } else if (category == "added_date") {
          $('#target').attr('type', 'date');
        }

      });

      ///////////////////////////// Block confirmation (single blood bag) ///////////////////////////
      $('#mng_invenTB').on('click', '.BlockConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Block This Blood Bag ?",
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

      ///////////////////////////// Block confirmation (bulk of bags) ///////////////////////////
      $('#QuickBlockBtn').on('click', function() {

        var cat = $('#BlockBy').val();
        var tar = $('#target').val();

        if (cat == "" || tar == "") {
          swal("Fill Required Field", "", "warning");
        } else {

          swal({
              title: "Are you sure You Want Block This Blood Bags Series ?",
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
                $('#quick_block').submit();
              }
            });

        }

        return false;

      });

      ///////////////////////////// Remove confirmation ///////////////////////////
      $('#mng_invenTB').on('click', '.RemoveConf', function() {

        var loc = $(this).attr('href');

        swal({
            title: "Are you sure You Want Remove This Blood Bag ?",
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