<?php
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>Verification</title>

	<?php
	include('../includes/css_assets.php');

	include('../includes/js_assets.php');
	?>
</head>

<body>

	<div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

		<div>&nbsp;</div>

		<!--Notice Row Start-->
		<div class="row">
			<div class="col-md-10 offset-1">

				<div class="card" style="border:#666 medium solid;">
					<div class="card-header text-center">

						<h2 style="letter-spacing:4px; color:#900;"><b>...<u>NOTICE</u>...</b></h2>

						<div class="card-body text-center shadow" style="background-color:#000;">
							<p style="font-size:18px; font-weight:bolder; color:#FFF;">
								*If you forgot your current password you can set new password for your account.<br />But first we need to verify you are the true owner of the account,<br /> So we will send 5 digits Verification code to your <u>'Registered Email address'</u>.<br />If You verified it correctly, Then you will be granted permission for that particular task.Thank you!
							</p>
						</div>

					</div>
				</div>

			</div>
		</div>
		<!--End Row-->

		<div>&nbsp;</div>

		<!--Code sending form Row-->
		<div class="row">
			<div class="col-md-4 offset-4">
				<!--cardStart-->
				<div class="cardpanel-info">
					<div class="card-body">

						<!--Form Start-->
						<form id="send_otpForm">
							<div class="form-group">
								<label class="alert-info" style="font-size:16px;">Email Address :</label>
								<input id="email" type="text" class="form-control" autocomplete="off" required="required" />
							</div>
							<input id="send_otpBtn" type="submit" class="btn btn-info" value="Send Code" />
						</form>
						<!--Form Close-->

					</div>

				</div>
			</div>
			<!--cardEnd-->
		</div>
	</div>
	<!--Row End-->

	<div>&nbsp;</div>

	<!--Time Countdown Row-->
	<div class="col-md-6 offset-3" id="countTime_row">
		<div class="alert alert-warning text-justify">
			<h4 id="count_time"></h4>
		</div>
	</div>
	<!---->

	<div>&nbsp;</div>

	<!--Code sumbit form Row-->
	<div class="row" id="subotp_row">
		<div class="col-md-4 offset-4">
			<!--cardStart-->
			<div class="cardpanel-success">
				<div class="card-body">
					<!--Form Start-->
					<form id="subotp_Form">
						<div class="form-group">
							<label class="alert alert-success" style="font-size:16px;">Type Code Here :</label>
							<input id="otp" type="number" class="form-control" autocomplete="off" />
						</div>
						<input type="submit" class="btn btn-success" value="Verify">
					</form>
					<!--Form End-->
				</div>

			</div>
		</div>
		<!--cardEnd-->

	</div>
	</div>
	<!--Row End-->


	<div class="modal fade" id="resetPW" role="dialog">
		<div class="modal-dialog modal-md">
			<form id="resetPw_Form">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title text-center">Reset Password</h5>
					</div>
					<div class="modal-body">
						<div class="row">
						</div>
						<div class="row">&nbsp;</div>
						<input type="hidden" id="succ_email" />
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="alert-dark">Enter New Password :</label>
									<div class="input-group">
										<input id="pw" name="pw" type="password" class="form-control" required>
										<span class="input-group-append">
											<span id="pw_addon" class="input-group-text" style="cursor:pointer;">
												<i id="pw_icon" class="fa fa-eye"></i>
											</span>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div>&nbsp;</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="alert-dark">Confirm Password :</label>
									<div class="input-group">
										<input id="cpw" type="password" class="form-control" required>
										<span class="input-group-append">
											<span id="cpw_addon" class="input-group-text" style="cursor:pointer;">
												<i id="cpw_icon" class="fa fa-eye"></i>
											</span>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div>&nbsp;</div>

						<button type="submit" class="btn btn-warning">Reset <i class="fa fa-edit"></i></button>
					</div>
				</div>

		</div>
	</div>
	</form>
	</div>
	</div>

	</div>

	<script src="../js/forgotPW.js"></script>
</body>

</html>