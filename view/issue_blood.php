<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 3) {
	header("HTTP/1.1 400 Unauthorized access");
	die("Access Denied");
}

$request_id = isset($_GET['request_id']) ? base64_decode($_GET['request_id']) : '';

if (isset($_GET['request_id'])) {

	include('../model/patient_model.php');
	$bloodReq_obj = new Blood_Request($conn);
	$result_req = $bloodReq_obj->get_SpecificRequests($request_id);
	$row_req = $result_req->fetch_assoc();

	$patient_obj = new Patient($conn);
	$result_pat = $patient_obj->patient_search($row_req['nic_no']);
	$row_pat = $result_pat->fetch_assoc();

	include('../model/inventory_model.php');
	$inventory_obj = new Inventory($conn);
	$result_crossMatch = $inventory_obj->crossMatch_check($row_pat['blood_grp']);

	include_once("../model/blood_model.php");
	$bloodgrp_obj = new Blood_Grp($conn);
	$pat_blood = $bloodgrp_obj->give_SpecificGroup($row_req['blood_grp']);
	$pat_blood_row = $pat_blood->fetch_assoc();

	$bloodcomp_obj = new Blood_Component($conn);
	$pat_comp = $bloodcomp_obj->give_SpecificComp($row_req['comp_type']);
	$pat_comp_row = $pat_comp->fetch_assoc();
}

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>Issue Blood</title>

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

			<?php
			if ($res_status == '1') {
			?>
				<!--Registered Response Msg-->
				<div class="col-md-8 offset-2">
					<div class="alert alert-success text-center">
						<h3 style="color:#090;"><b><?php echo $msg; ?></b></h3>
					</div>
				</div>
				<!---->
				<div>&nbsp;</div>
			<?php
			} else if ($res_status == '0') {
			?>
				<!--Registered Response Msg-->
				<div class="col-md-8 offset-2">
					<div class="alert alert-danger text-center">
						<h3 style="color:#C10000;"><b><?php echo $msg; ?></b></h3>
					</div>
				</div>
				<!---->
				<div>&nbsp;</div>
			<?php
			}
			?>

			<!--Row Start-->
			<div class="row">

				<div class="col-md-10 offset-1">

					<div class="cardpanel-dark">
						<div class="card-header text-center bg-dark" style="color:#FFF;">
							<i class="fa fa-share-square fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Issue Blood</b></span>
						</div>
						<div class="card-body">
							<!--Reg Form Start-->
							<form id="" action="../controller/inventory_controller.php?status=issue_blood&request_id=<?php echo $request_id; ?>" method="post">

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label class="alert-dark">Patient N.I.C. :</label>
											<input id="nic" value="<?php if (isset($_GET['request_id'])) {
																		echo $row_req['nic_no'];
																	} ?>" type="text" class="form-control" required autocomplete="off" readonly>
										</div>
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label class="alert-dark">Patient Name :</label>
											<input type="text" value="<?php if (isset($_GET['request_id'])) {
																			echo $row_pat['patient_name'];
																		} ?>" class="form-control" readonly>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label class="alert-dark">Patient's Blood Group :</label>
											<input type="text" value="<?php if (isset($_GET['request_id'])) {
																			echo $pat_blood_row["grp_name"];
																		} ?>" class="form-control" readonly>
										</div>
									</div>

								</div>

								<div class="row mt-3">

									<div class="col-md-4">
										<div class="form-group">
											<label class="alert-dark">Cross Matching Blood Types :</label>
											<div>
												<?php if (isset($_GET['request_id'])) {
													while ($row_crossMatch = $result_crossMatch->fetch_assoc()) {
														$single_blood_result = $bloodgrp_obj->give_SpecificGroup($row_crossMatch['match_id']);
														$single_blood_row = $single_blood_result->fetch_assoc();
												?>
														<h6 style="display:inline; color:#F00; font-weight:bold;"><?php echo $single_blood_row["grp_name"]; ?></h6>&nbsp;
														<input type="hidden" class="crossMatch" value="<?php echo $single_blood_row["grp_id"]; ?>">
												<?php }
												} ?>
											</div>
										</div>
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label class="alert-dark">Component Type :</label>
											<input type="text" value="<?php if (isset($_GET['request_id'])) {
																			echo $pat_comp_row["comp_name"];
																		} ?>" class="form-control" readonly>
											<input type="hidden" id="comp_type" value="<?php echo $pat_comp_row["comp_id"] ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label class="alert-dark">Request Count(Bags) :</label>
											<input id="req_amount" type="number" value="<?php if (isset($_GET['request_id'])) {
																							echo $row_req['require_amount'];
																						} ?>" class="form-control" required autocomplete="off" readonly>
										</div>
									</div>

								</div>

								<hr>

								<div class="row mt-3">

									<div class="col-md-4">
										<div class="form-group">
											<label class="alert-dark">Available Count(Bags) :</label>
											<input type="number" id="av_count" class="form-control" readonly>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="alert-dark">Bag IDs To Be Issued :</label>
											<div style="overflow:auto; max-height:400px;" id="ToBeIssue">

											</div>
										</div>
									</div>

								</div>

								<input type="hidden" value="<?php if (isset($_GET['request_id'])) {
																echo $row_pat['patient_id'];
															} ?>" name="pat_id">
								<input type="hidden" value="<?php if (isset($_GET['request_id'])) {
																echo $row_req['hospital'];
															} ?>" name="hosp">

								<button id="submit" type="submit" class="btn btn-warning"><b><span class="fa fa-share-square"></span> Issue</b></button>

							</form>
							<!--Reg Form End-->
						</div>
					</div>

				</div>

			</div>
			<!--Row End-->

			<div>&nbsp;</div>

		</div>

		<!-- ///////////////////////// Content end ///////////////////////// -->
	</div>
	</div>


	<script>
		$(document).ready(function(e) {

			/////////////////////////// Insert all cross matching gruop IDs into the array ///////////////////////////
			var CM_grp = [];

			$('.crossMatch').each(function(index) {

				var grp_id = $(this).val();
				CM_grp.push(grp_id);

			});

			/////////////////////////// After complete previous process send all matching groups and components type by ajax ///////////////////////////
			setTimeout(function() {

				var comp = $('#comp_type').val();
				var req_amount = $('#req_amount').val();

				if (comp != 0) {

					$.ajax({
						url: "../controller/inventory_controller.php?status=get_MatchingBags",
						type: 'POST',
						dataType: "JSON",
						data: {
							comp: comp,
							req_amount: req_amount,
							cm_grp: CM_grp
						},
						success: function(data) {
							$('#ToBeIssue').html(data[0]);
							$('#av_count').val(data[1]);

							//////////////// If inventory has no matching blood bags to issue ////////////////
							if (data[1] < 1) {
								$('#av_count').css({
									border: '#F00 solid 4px'
								});
								$('#submit').prop('disabled', true);

								swal("Not Matching Bags Available to Issue", "Cannot Performe Task", "error");

							} else if (data[1] < req_amount) {
								//////////////// If inventory has less amount of required blood bags amount to issue ////////////////
								swal({
									title: "Not Enough Bags Available to Issue",
									text: "Do you want issue available bags anyway ?",
									type: "error",
									showCancelButton: true,
									confirmButtonClass: "btn-danger",
									confirmButtonText: "Yes, Issue",
									cancelButtonClass: "btn-info",
									cancelButtonText: "No, Don't",
									closeOnConfirm: true
								}, function(isConfirm) {
									if (isConfirm === true) {
										$('#submit').prop('disabled', false);
									}
								});
								$('#av_count').css({
									border: '#F00 solid 4px'
								});
								$('#submit').prop('disabled', true);
							} else {
								//////////////// If inventory has enough blood bags to issue ////////////////
								swal({
									title: "Ready To Issue !",
									type: "success",
									timer: 1500,
									showConfirmButton: false,
									closeOnConfirm: false
								});
								$('#submit').prop('disabled', false);
								$('#av_count').css({
									border: '#0F0 solid 4px'
								});
							}
						}
					});

				} else {
					$('#ToBeIssue').html("");
					$('#av_count').val(0);
				}

			}, 500);

		});
	</script>
</body>

</html>