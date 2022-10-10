<?php
include('../includes/session&redirect.php');

if ($_SESSION["user_role"] != 100 && $_SESSION["user_role"] != 1 && $_SESSION["user_role"] != 6) {
	header("HTTP/1.1 400 Unauthorized access");
	die("Access Denied");
}

include_once('../model/camp_model.php');
$camp_object = new Camp($conn);
$result = $camp_object->give_CampTB_AI();
$next_id = $result->fetch_assoc();

include('../model/Data_model.php');
$loc_object = new Location($conn);
$loc_result = $loc_object->get_allDistricts();

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

			<!-- ///////////////////////// Response alert ///////////////////////// -->
			<?php include_once("../includes/res_alert.php"); ?>

			<div>&nbsp;</div>

			<!--2nd Row Start-->
			<div class="row mt-3">

				<div class="col-md-10 offset-1">

					<div class="card">
						<div class="card-header text-center bg-dark text-white">
							<i class="fa fa-tent fa-3x fa-pull-left"> </i> <span style="font-size:28px; letter-spacing:5px;"><b>Add Blood Camp</b></span>
						</div>
						<div class="card-body">
							<form id="add_camp" action="../controller/camp_controller.php?status=add_camp" method="post">

								<div class="row">
									<input id="camp_id" name="camp_id" value="<?php echo $next_id['Auto_increment']; ?>" type="hidden" class="form-control" readonly>

									<div class="col-md-8">
										<div class="form-group">
											<label>Organizer's Name :</label>
											<input id="org_name" name="org_name" type="text" class="form-control" autocomplete="off" required>
										</div>
									</div>

									<div class="col-md-8">
										<div class="form-group">
											<label>Venue :</label>
											<input name="location" type="text" class="form-control" autocomplete="off" required>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label>District :</label>
											<select class="form-control" name="disc_id" required>
												<option value=""></option>
												<?php while ($loc_row = $loc_result->fetch_assoc()) { ?>
													<option value="<?php echo $loc_row['district_id']; ?>"><?php echo $loc_row['district_name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Expected Donors Level :</label>
											<input id="tar_donors" name="tar_donors" type="number" class="form-control" required>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label>Date :</label>
											<input id="date" name="date" type="date" class="form-control" required readonly>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label>Starting Time :</label>
											<input name="time" type="time" class="form-control" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label>Other Info :</label>
											<textarea name="other_info" class="form-control"></textarea>
										</div>
									</div>

								</div>

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

								<button id="submit" type="submit" class="btn btn-success"><b><span class="fa fa-plus"></span> ADD</b></button>

							</form>
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

	<script src="../js/addBloodCamp.js"></script>

</body>

</html>