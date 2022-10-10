<?php
ini_set('session.cookie_lifetime', '86400');
session_start();

///////////////////////// Include user model /////////////////////////
include('../model/user_model.php');
$user_object = new User($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'add_user':

		////////// Get required variables from add user form //////////
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$resno    = (isset($_POST['resno']) && !empty($_POST['resno'])) ? "0" . $_POST['resno'] : '';
		$mno      = "07" . $_POST['mno'];
		$nic      = trim($_POST['nic']);
		$dob      = $_POST['dob'];
		$gender   = $_POST['gender'];
		$city     = trim($_POST['city']);
		$email    = trim($_POST['email']);
		$uname    = trim($_POST['uname']);
		$password = $_POST['pw'];
		$enc_password = md5($password);
		$role     = $_POST['role'];

		////////// If user image not empty //////////
		if ($_FILES["uimg"]["name"] != "") {

			////////// Set new file name as add current date //////////
			$uimg = $_FILES["uimg"]["name"];
			$uimg = "" . time() . "_" . $uimg;
		} else {
			////////// If user image is empty, then set user image as default image //////////
			$uimg = "Default_Img.jpg";
		}

		$tmp_location = $_FILES["uimg"]["tmp_name"]; //tempory storage location
		$permanant = "../images/users/$uimg"; //Permanent Location (Moving Location)

		move_uploaded_file($tmp_location, $permanant);

		////////// Execute add user to user table method //////////
		$result_1 = $user_object->create_user_userTB($fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg);

		////////// If add user method success //////////
		if ($result_1) {
			////////// Get inserted user ID //////////
			$user_id  = $result_1;
			////////// Execute add login method //////////
			$result_2 = $user_object->create_user_logTB($user_id, $uname, $enc_password);

			////////// If add login method success //////////
			if ($result_2) {

				///////////////////////// Send email to the user to confirm registration with username and password  /////////////////////////
				include_once('../controller/mail_controller.php');
				$mail_object = new mail_send();

				$subject = "Registration Confirmation";
				$body = "<h3>Mr./Mrs. $fname $lname, You have added as a new user to the BTMS system.</h3> <h4><u>Login credentials</u></h4> Username : $uname or this email address <br>Password : $password";

				$mail_object->Send_Mail($email, $subject, $body);

				$msg = "Successfully Added User " . $fname . " " . $lname . "";
				$res_status = '1';
			} else {
				////////// If add login methods fail //////////
				$msg = "oops... something went wrong please try again later";
				$res_status = '0';
			}
		} else {
			////////// If add user methods fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

?>

		<script>
			window.location = "../view/add_user.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>

	<?php

		break;

	case 'user_search':

		////////// Get user NIC from ajax request //////////
		$nic = trim($_POST['nic']);

		////////// Get user details by NIC //////////
		$result = $user_object->getUser_ByNIC($nic);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'uname_search':

		////////// Get username from ajax request //////////
		$uname = trim($_POST['uname']);

		////////// Get user details by username //////////
		$result = $user_object->getUser_ByUname($uname);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'get_DetailsByUname':

		////////// Get username and password from ajax request //////////
		$uname = trim($_POST['uname']);
		$pw    = trim($_POST['pw']);

		////////// Get user details by username //////////
		$result = $user_object->getUser_ByUname($uname);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			if ($row['password'] == md5($pw)) {
				echo $row['user_id'];
			} else {
				echo "NoData";
			}
		} else {
			////////// If result has no records //////////
			echo "NoData";
		}

		break;

	case 'get_usersByRole':

		////////// Get role ID from ajax request //////////
		$role_id = trim($_POST['role']);

		if ($role_id == "all") {
			////////// Get all users //////////
			$result = $user_object->give_Allusers();
		} else {
			////////// Get users by role ID //////////
			$result = $user_object->give_usersByRole($role_id);
		}

		$output = "<option value=''>---</option>";

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			$output .= "<option value='-1'>All</option>";
			////////// Fetch data //////////
			while ($row = $result->fetch_assoc()) {
				$output .= "<option value='" . $row['user_id'] . "'>" . $row['fname'] . " " . $row['lname'] . "</option>";
			}
		}

		////////// Pass the data //////////
		echo $output;

		break;

	case 'email_search':

		////////// Get user email from ajax request //////////
		$email = trim($_POST['email']);

		////////// Get user details by email //////////
		$result = $user_object->give_userByEmail($email);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'check_AdminPw':

		////////// Get user ID and password from ajax request //////////
		$user_id = $_POST['user_id'];
		$pw = md5($_POST['pw']);

		////////// Get user details by user ID //////////
		$result = $user_object->give_info($user_id);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			////////// Fetch data //////////
			$row = $result->fetch_assoc();
			////////// Check password //////////
			if ($pw === $row['password']) {
				echo "ok";
			}
		} else {
			////////// If result has no records //////////
			echo "error";
		}

		break;

	case 'save_changeUser':

		////////// Get required variables from update user info form //////////
		$user_id  = $_REQUEST['user_id'];

		////////// Get user details by user ID //////////
		$get_user = $user_object->give_info($user_id);
		$row_user = $get_user->fetch_assoc();

		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$resno    = "0" . $_POST['resno'];
		$mno      = "07" . $_POST['mno'];
		$nic      = trim($_POST['nic']);
		$dob      = $_POST['dob'];
		$gender   = $_POST['gender'];
		$city     = trim($_POST['city']);
		$email    = trim($_POST['email']);
		$uname    = trim($_POST['uname']);
		$role     = isset($_POST['role']) ? $_POST['role'] : $_SESSION['user_role'];
		$uimg     = ($_FILES["uimg"]["name"] != "") ? $_FILES["uimg"]["name"] : $_POST['defUimg'];

		////////// If user image has been changed //////////
		if ($uimg != $row_user['user_img']) {

			////////// Set new file name as add current date //////////
			$uimg = $_FILES["uimg"]["name"];
			$uimg = "" . time() . "_" . $uimg;

			$tmp_location = $_FILES["uimg"]["tmp_name"]; //tempory storage location
			$permanant = "../images/users/$uimg"; //Permanent Location (Moving Location)

			move_uploaded_file($tmp_location, $permanant);

			if ($_POST["defUimg"] != "Default_Img.jpg") {
				unlink("../images/users/" . $_POST["defUimg"]);
			}
		}

		////////// Execute update user info method //////////
		$result = $user_object->update_info($user_id, $fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg);

		////////// If method success //////////
		if ($result) {

			////////// Session stored user image should be change //////////
			if ($_SESSION['user_id'] == $user_id) {
				$_SESSION['user_img']  = $uimg;
			}
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);


	?>
		<script>
			window.location = "../view/display_UserDetails.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&user_id=<?php echo base64_encode($user_id); ?>";
		</script>
	<?php

		break;

	case 'save_changeInfo':

		////////// Get required variables from update self info form //////////
		$user_id  = $_SESSION['user_id'];

		$get_user = $user_object->give_info($user_id);
		$row_user = $get_user->fetch_assoc();

		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$resno    = "0" . $_POST['resno'];
		$mno      = "07" . $_POST['mno'];
		$nic      = trim($_POST['nic']);
		$dob      = $_POST['dob'];
		$gender   = $_POST['gender'];
		$city     = trim($_POST['city']);
		$email    = trim($_POST['email']);
		$role     = $_SESSION['user_role'];
		$uimg     = ($_FILES["uimg"]["name"] != "") ? $_FILES["uimg"]["name"] : $_POST['defUimg'];

		////////// If user image has been changed //////////
		if ($uimg != $row_user['user_img']) {

			////////// Set new file name as add current date //////////
			$uimg = $_FILES["uimg"]["name"];
			$uimg = "" . time() . "_" . $uimg;

			$tmp_location = $_FILES["uimg"]["tmp_name"]; //tempory storage location
			$permanant = "../images/users/$uimg"; //Permanent Location (Moving Location)

			move_uploaded_file($tmp_location, $permanant);

			if ($_POST["defUimg"] != "Default_Img.jpg") {
				unlink("../images/users/" . $_POST["defUimg"]);
			}
		}

		////////// Execute update user info method //////////
		$result = $user_object->update_info($user_id, $fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg);

		////////// If method success //////////
		if ($result) {
			////////// Session stored user image should be change //////////
			$_SESSION['user_img']  = $uimg;

			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);


	?>
		<script>
			window.location = "../view/self_info.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'active_block':

		////////// Get user ID and current status from view page //////////
		$user_id     = $_REQUEST['user_id'];
		$user_status = $_REQUEST['user_status'];
		$switch_status = ($user_status == 1) ? 0 : 1;

		////////// Execute change user status method //////////
		$result = $user_object->update_status($user_id, $switch_status);

		////////// If method success //////////
		if ($result) {
			$msg = "Change Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_user.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'remove_user':

		////////// Get user ID from view page //////////
		$user_id     = $_REQUEST['user_id'];

		////////// Execute remove user method //////////
		$result = $user_object->delete_user($user_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Remove User Successfully";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_user.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

}
?>