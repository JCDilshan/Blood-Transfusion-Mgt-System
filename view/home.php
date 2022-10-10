<?php
include('../includes/session&redirect.php');

include('../model/user_model.php');
$user_object = new User($conn);
$result_users = $user_object->give_Allusers();

include('../model/donor_model.php');
$donor_object = new Donor($conn);
$top_donors = $donor_object->give_TopDonors();
$result_donors = $donor_object->give_Alldonors();

include('../model/schedule_model.php');
$appoint_object = new Appointment($conn);
$result_appoint = $appoint_object->give_ListedAppointments();

include('../model/camp_model.php');
$camp_object = new Camp($conn);
$result_camps = $camp_object->give_Allcamps();

include('../model/staff_model.php');
$doctor_object = new Doctor($conn);
$nurse_object = new Nurse($conn);
$member_object = new Other_Staff($conn);
$result_doctors = $doctor_object->give_Alldoctors();
$result_nurse = $nurse_object->give_AllNurse();
$result_mems =  $member_object->give_AllMembers();

include('../model/patient_model.php');
$BloodReq_object = new Blood_Request($conn);
$result_req = $BloodReq_object->give_PendingRequests();

include_once("../model/blood_model.php");
$blood_object = new Blood_Grp($conn);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>Home</title>

	<?php
	include('../includes/css_assets.php');
	include('../includes/js_assets.php');
	?>

	<link rel="stylesheet" href="../css/home.css">

</head>

<body>

	<!-- ///////////////////////// Content wrapper start ///////////////////////// -->
	<div class="wrapper d-flex align-items-stretch">

		<?php
		include('../includes/nav_bar.php');
		?>

		<div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

			<div class="row mt-2">

				<div class="col-md-12">

					<div id="carouselExampleControls" class="carousel slide shadow-lg" data-ride="carousel">
						<div class="carousel-inner">
							<div class="carousel-item active">
								<img class="d-block w-100 slidesImg" src="../images/slide_images/blood-bankmod.jpg" alt="First slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100 slidesImg" src="../images/slide_images/wp4323457-blood-donation-wallpapersmod.jpg" alt="Second slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100 slidesImg" src="../images/slide_images/wp4323473-blood-donation-wallpapersmod.jpg" alt="Third slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100 slidesImg" src="../images/slide_images/wp4323556-blood-donation-wallpapersmod.jpg" alt="Fourth slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100 slidesImg" src="../images/slide_images/2381649mod.jpg" alt="Fifth slide">
							</div>
							<div class="carousel-item">
								<img class="d-block w-100 slidesImg" src="../images/slide_images/wp4323478-blood-donation-wallpapersmod.jpg" alt="Sixth slide">
							</div>
						</div>
						<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>

				</div>

			</div>

			<div class="row mt-3">

				<div class="col-md-6">
					<h2 style="color:#FFF; background-color:#666; text-align:center;"><strong>Highest Donaters</strong></h2>
					<div class="row">
						<?php
						$count = 1;
						while ($top_donors_arr = $top_donors->fetch_assoc()) { ?>

							<div class="col-md-4 col-sm-6">
								<div class="card h-100">
									<img class="card-img-top" src="../images/blood-donor.jpg" alt="" height="100">
									<?php if ($count == 1) { ?>
										<img class="reward_img" src="../images/icons/with-first-placement-png-image.jpg">
									<?php } ?>
									<div class="card-block">
										<p class="card-title pl-2" style="height:40px;"><u><b><a href="../report/DisplayDonor_report.php?donor_id=<?php echo base64_encode($top_donors_arr['donor_id']); ?>"><?php echo $top_donors_arr['donor_name']; ?></a></b></u></p>
										<p class="card-text pl-2" style="color:#333; height:6px;"><b>Blood Group :
												<?php
												$singleBlood_info = $blood_object->give_SpecificGroup($top_donors_arr['blood_grp']);
												$singleBlood_array = $singleBlood_info->fetch_assoc();
												echo $singleBlood_array["grp_name"];
												?>
											</b></p>
										<p class="card-text pl-2" style="color:#333;"><b>Donated Bags :</b> <span style="font-size:20px; color:#F60;"><?php echo $top_donors_arr['donated_count']; ?></span></p>
									</div>
								</div>
							</div>
							<?php
							if ($count % 3 == 0) {
							?>
					</div>
					<br>
					<div class="row">
				<?php }
							$count++;
						} ?>
					</div>
				</div>


				<div class="col-md-3">

					<div class="row ListedRows pt-2 h-25">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="alert-success text-center pt-2 h-100">
								<i class="fa fa-user fa-2x"></i>
								<h6 class="fa-3x"><?php echo $result_appoint->num_rows; ?></h6>
								<span style="font-size:1em;"><b>Listed Appointments</b></span>
							</div>
						</div>
					</div>

					<div class="row ListedRows pt-2 h-25">
						<div class="col-md-12 col-sm-6 col-xs-12">
							<div class="alert-success text-center pt-2 h-100">
								<i class="fa fa-campground fa-2x"></i>
								<h6 class="fa-3x"><?php echo $result_camps->num_rows; ?></h6>
								<span style="font-size:1em;"><b>Listed Blood Camps</b></span>
							</div>
						</div>
					</div>

					<div class="row ListedRows pt-2 h-25">
						<div class="col-md-12 col-sm-6 col-xs-12">
							<div class="alert-success text-center pt-2 h-100">
								<i class="fa fa-user fa-2x"></i>
								<h6 class="fa-3x"><?php echo $result_req->num_rows; ?></h6>
								<span style="font-size:1em;"><b>Listed Blood Requests</b></span>
							</div>
						</div>
					</div>

					<div class="row ListedRows pt-2 h-25">
						<div class="col-md-12 col-sm-6 col-xs-12">
							<div class="alert-success text-center pt-2 h-100">
								<i class="fa fa-users fa-2x"></i>
								<h6 class="fa-3x"><?php echo $result_users->num_rows; ?></h6>
								<span style="font-size:1em;"><b>Registered Users</b></span>
							</div>
						</div>
					</div>

				</div>


				<div class="col-md-3">

					<div class="card shadow h-100">
						<div class="card-header text-center" style="background:linear-gradient(to right, #060, #090);">
							<h6 style="color:#FFF;"><i class="fa fa-users"></i> Active Users</h6>
						</div>
						<div class="card-body p-1 pt-2" style="max-height:100%; overflow:auto;">
							<table cellspacing="0" style="width:100%; border-collapse: collapse;">
								<?php while ($row = $result_users->fetch_assoc()) {
									if ($row['login_status'] == 1) { ?>
										<tr style="border-bottom:#CCC thin solid;">
											<?php
											include_once('../model/role_model.php');
											$role_object = new Role($conn);
											$result_role = $role_object->get_specificRole($row['user_role']);
											$row_role = $result_role->fetch_assoc();
											?>
											<td style="padding-bottom:5px; width:24%;">
												<img src="../images/users/<?php echo $row['user_img'] ?>" width="35" height="35" style="border-radius:50%;">
												<span id="online_icon" class="fa fa-circle"></span>
											</td>
											<td style="font-size:11px; color:#000; font-weight:bolder; text-align:left;">
												<?php echo " " . $row['fname'] . " " . $row['lname'] . "<br> (" . $row_role['role_name'] . ")"; ?></td>
										</tr>
								<?php }
								} ?>
							</table>
						</div>
					</div>

				</div>

			</div>


			<div class="row mt-3 ListedRows">

				<div class="col-md-3 col-sm-4 col-xs-8">
					<div class="alert alert-warning text-center shadow">
						<i class="fa fa-users fa-3x"></i>
						<h6 class="fa-2x"><?php echo $result_donors->num_rows; ?></h6>
						<span style="font-size:20px;">Registered Donors</span>
					</div>
				</div>

				<div class="col-md-3 col-sm-4 col-xs-8">
					<div class="alert alert-warning text-center shadow">
						<i class="fa fa-user-tie fa-3x"></i>
						<h6 class="fa-2x"><?php echo $result_doctors->num_rows; ?></h6>
						<span style="font-size:20px;">Total Docotors</span>
					</div>
				</div>

				<div class="col-md-3 col-sm-4 col-xs-8">
					<div class="alert alert-warning text-center shadow">
						<i class="fa fa-user-nurse fa-3x"></i>
						<h6 class="fa-2x"><?php echo $result_nurse->num_rows; ?></h6>
						<span style="font-size:20px;">Total Nurse</span>
					</div>
				</div>

				<div class="col-md-3 col-sm-4 col-xs-8">
					<div class="alert alert-warning text-center shadow">
						<i class="fa fa-users fa-3x"></i>
						<h6 class="fa-2x"><?php echo $result_mems->num_rows; ?></h6>
						<span style="font-size:20px;">Other Staff Members</span>
					</div>
				</div>

			</div>

			<div class="row mt-3">

				<div class="col-md-12 col-sm-6 col-xs-12">

					<div class="cardpanel-danger shadow">
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

		</div>

		<!--Must Be contain-->
	</div>
	</div>

	<script src="../js/mycharts2.js"></script>

</body>

</html>