<?php
ini_set('session.cookie_lifetime', '86400');
session_start();

////////// Include user model ////////// 
include('../model/user_model.php');
$user_object = new User($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

//////////// Switch request status //////////////
switch ($status) {

	case 'login':

		////////// Get Required Variables From Login Form //////////
		$uname    = $_POST['uname'];
		$password = $_POST['password'];
		$password = md5($password); ////////// Encrypt Password for Compare with Sotred Password //////////

		////////// Execute Login Method //////////
		$result = $user_object->login($uname, $password);

		////////// If Query Execute Correctly ////////// 
		if ($result) {
			////////// Get Records Count //////////
			$row_count = $result->num_rows;
			////////// If Record Found with Given Email and Password  //////////
			if ($row_count == 1) {
				////////// Make Assosiative Array //////////
				$array = $result->fetch_assoc();
				////////// If He/She is an Active User //////////
				if ($array['user_status'] == 1) {
					////////// Create Session Variables for Particular User //////////
					$_SESSION['user_id']   = $array['user_id'];
					$_SESSION['user_name'] = $array['fname'] . " " . $array['lname'];
					$_SESSION['password']  = $array['password'];
					$_SESSION['user_role'] = $array['user_role'];
					$_SESSION['user_img']  = $array['user_img'];
					$user_id = $_SESSION['user_id'];

					////////// Set Login Status to Online //////////
					$user_object->update_login_stmt($user_id);
					////////// If He/She's Role is Administrator //////////
					if ($array['user_role'] == 1 || $array["user_role"] == 100) {
						////////// Redirect to the Admin Dashboard //////////
?>
						<script>
							window.location = '../view/dashboard.php';
						</script>
					<?php
						////////// Otherwise Redirect to The General Home Page //////////
					} else {
					?>
						<script>
							window.location = '../view/home.php';
						</script>
		<?php
					}
					////////// If User has Blocked //////////
				} else {
					$msg = "Sorry..! Your Account Has Been Blocked. Please Contact Admin For More Details";
					$res_status = '0';
				}
				////////// If Login Credentials Does't Match //////////
			} else {
				$msg = "Invalid Login details";
				$res_status = '0';
			}
			////////// If Has an Error //////////
		} else {
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and Send it to The view Page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
		?>
		<script>
			window.location = '../view/login.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>';
		</script>
	<?php

		break;

	case 'change_pw':

		////////// Get Required Variables From password change Form //////////
		$cur_pw    = $_POST['cur_pw'];
		$cur_pw   = md5($cur_pw);
		$new_pw    = $_POST['new_pw'];
		$new_pw   = md5($new_pw);

		$user_id   = $_SESSION['user_id'];
		$password  = $_SESSION['password'];

		//////////// Check current password /////////////
		if ($password == $cur_pw) {
			////////// Execute update password method //////////
			$result = $user_object->pw_update($user_id, $new_pw);
			////////// If Query Execute Correctly ////////// 
			if ($result) {
				$msg = "Changed password successfully !";
				$res_status = '1';
				/////////// Set new password to Session as well ///////////
				$_SESSION['password'] = $new_pw;
			} else {
				////////// If error occured ////////// 
				$msg = "oops... something went wrong please try again later";
				$res_status = '0';
			}
		} else {
			////////// If current password incorrect ////////// 
			$msg = "Current password incorrect";
			$res_status = '0';
		}

		////////// Encode Response Messages and Send it to The view Page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = '../view/change_pw.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>';
		</script>

<?php

		break;

	case 'send_otp':

		////////// Get user email ////////// 
		$email   = trim($_POST['email']);
		////////// Genarate random number(OTP) ////////// 
		$otp_gen = rand(10000, 99999);

		////////// Get user details by email address ////////// 
		$check_email = $user_object->give_userByEmail($email);

		////////// If it is registered email ////////// 
		if ($check_email->num_rows == 1) {

			////////// Include mail controller file ////////// 
			include_once('mail_controller.php');
			$mail_object = new mail_send();

			///////////////////////// Set email subject and body /////////////////////////
			$subject = "Account Identification";
			$body = "<h3>Your Verification code is :- </h3>" . "<h2>" . $otp_gen . "</h2><br/><h4>Notice : Code will be expires after 90 seconds</h4>";

			///////////////// store generated random number(OTP) within session ///////////////
			$_SESSION[$email . "_otp"] = $otp_gen;

			//////////////// Send email ///////////////
			$mail_result = $mail_object->Send_Mail($email, $subject, $body);


			if (!$mail_result) {
				//////////////// If email not sent ////////////////
				$res_status = 'sendFail';
			} else {
				//////////////// If email sent successfully ////////////////
				$res_status = 'sendOk';
			}
		} else {
			//////////////// If user's email is not registered ////////////////
			$res_status = "NoData";
		}

		////////// Json encode and send data //////////
		echo json_encode(array($email, $res_status));

		break;

	case 'sub_otp':

		//////////////// Get user email address and entered OTP ////////////////
		$email = trim($_POST['email']);
		$otp = trim($_POST['otp']);

		//////////////// If OTP credentials match ////////////////
		if ($otp == $_SESSION[$email . "_otp"]) {
			$res_status = "ok";
			//////////////// Unset OTP session ////////////////
			unset($_SESSION[$email . "_otp"]);
		} else {
			//////////////// If OTP entered incorrectly ////////////////
			$res_status = "fail";
		}

		////////// Json encode and send data //////////
		echo json_encode(array($email, $res_status));

		break;

	case 'reset_pw':

		//////////////// Get user email and new password ////////////////
		$new_pw = md5($_POST['new_pw']);
		$email = $_POST['email'];

		//////////////// Get user details by email ////////////////
		$get_userID = $user_object->give_userByEmail($email);
		//////////////// Fetch data ////////////////
		$user_row = $get_userID->fetch_assoc();

		//////////////// Get user ID ////////////////
		$user_id = $user_row['user_id'];

		//////////////// Update user's password as new password ////////////////
		$result = $user_object->pw_update($user_id, $new_pw);

		//////////////// If process success ////////////////
		if ($result) {
			echo "success";
		} else {
			//////////////// If process fail ////////////////
			echo "fail";
		}

		break;

	case 'logout':

		////////// Get current user ID from sessions //////////
		$user_id = $_SESSION['user_id'];

		////////// Set Offline Status ////////
		$result = $user_object->update_logout_stmt($user_id);

		////////// Destroy all sessions //////////
		session_destroy();

		////////// Redirect to the login page //////////
		header('location: ../view/login.php');
		break;
}

?>