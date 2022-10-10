<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
	header("HTTP/1.1 400 Unauthorized access");
	die("Access Denied");
}

$camp_id = isset($_REQUEST['camp_id']) ? base64_decode($_REQUEST['camp_id']) : '';

include('../model/camp_model.php');
$camp_object = new Camp($conn);
$camp_result = $camp_object->get_specificCamp($camp_id);
$camp_row = $camp_result->fetch_assoc();

include('../model/campRefers_model.php');
$doc_campObj = new Doctor_camp($conn);
$nurse_campObj = new Nurse_camp($conn);
$mem_campObj = new Member_camp($conn);

include('../model/staff_model.php');
$doctor_object = new Doctor($conn);
$nurse_object  = new Nurse($conn);
$member_object = new Other_Staff($conn);

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>Add Camp</title>

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

			<?php
			if ($res_status == '1') {
			?>
				<br>
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

			<div>&nbsp;</div>

			<!--2nd Row Start-->
			<div class="row mt-3">

				<div class="col-md-10 offset-1">

					<div class="card">
						<div class="card-header text-center bg-dark">
							<i class="fa fa- fa-3x fa-pull-left"> </i> <span style="font-size:28px; color:#FFF; letter-spacing:5px;"><b>View Blood Camp</b></span>
						</div>
						<div class="card-body">
							<form id="edit_camp" action="../controller/camp_controller.php?status=edit_camp" method="post">

								<input type="hidden" name="camp_id" id="camp_id" value="<?php echo $camp_id; ?>">
								<div class="row">

									<div class="col-md-8">
										<div class="form-group">
											<label>Organizer's Name :</label>
											<input id="org_name" name="org_name" value="<?php echo $camp_row['organizer_name']; ?>" type="text" class="form-control" autocomplete="off" required readonly>
										</div>
									</div>

									<div class="col-md-8">
										<div class="form-group">
											<label>Venue :</label>
											<input name="location" type="text" value="<?php echo $camp_row['location']; ?>" class="form-control" autocomplete="off" required readonly>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label>Target Donors Level :</label>
											<input id="tar_donors" name="tar_donors" value="<?php echo $camp_row['target_donors']; ?>" type="number" class="form-control" required readonly>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Date :</label>
											<input id="date" name="date" value="<?php echo $camp_row['date']; ?>" type="date" class="form-control" required readonly>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Starting Time :</label>
											<input name="time" type="time" value="<?php echo $camp_row['start_time']; ?>" class="form-control" readonly>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label>Other Info :</label>
											<textarea name="other_info" value="<?php echo $camp_row['other_info']; ?>" class="form-control" readonly></textarea>
										</div>
									</div>

								</div>

								<div>&nbsp;</div>

								<h5><u>Assign Staff</u></h5>

								<h6 style="color:#333;"><b>Doctors :-</b></h6>
								<div class="row" id="doc_count">

								</div>
								<hr>
								<h6 style="color:#333;"><b>Nurses :-</b></h6>
								<div class="row" id="nurse_count">

								</div>
								<hr>
								<h6 style="color:#333;"><b>Other Helping Staff :-</b></h6>
								<div class="row" id="mem_count">

								</div>
								<input type="hidden" value="0" id="editStmt" name="editStmt">
								<?php if ($camp_row['held_status'] == 0) { ?>
									<button type="button" id="edit" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
									<br><br>
									<button id="submit" type="submit" class="btn btn-success" disabled hidden><b><span class="fa fa-save"></span> Save Changes</b></button>
								<?php } ?>
							</form>
							<hr>
							<h5 style="color:#F33;">NOTE: If you Leave This Page Without Save Changes While Modifing, Then Camp Will Be Save With Old Details Again.</h5>
						</div>
					</div>

				</div>

			</div>
			<!--2nd Row End-->

			<div>&nbsp;</div>

		</div>

		<!-- ///////////////////////// Content end ///////////////////////// -->
	</div>
	</div>

	<script src="../js/editCampDetails.js"></script>

</body>

</html>