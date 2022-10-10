<?php
///////////////////////// Include donor model /////////////////////////
include('../model/donor_model.php');
$donor_object = new Donor($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'add_donor':

		////////// Get Required Variables From Add Donor Form //////////
		$name     = trim($_POST['name']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$dob      = $_POST['dob'];
		$gender   = $_POST['gender'];
		$email    = isset($_POST['email']) ? trim($_POST['email']) : NULL;
		$blood_grp = $_POST['blood_grp'];
		$disc_id        = $_POST['disc_id'];
		$otherInfo = trim($_POST['otherInfo']);

		////////// Execute Add Donor Method //////////
		$result = $donor_object->add_donor($name, $nic, $gender, $dob, $con_no, $disc_id, $email, $blood_grp, $otherInfo);

		////////// If Successfully Added //////////
		if ($result) {
			$msg = "Successfully Added Donor " . $name;
			$res_status = '1';

			////////// Send Email to The Donor to Inform His/Her Redistration //////////
			if ($email != NULL) {
				include_once('../controller/mail_controller.php');
				$mail_object = new mail_send();

				$subject = "Donor Registration";
				$gender = ($gender == "M") ? "Mr." : "Mrs.";
				$body = "<h3>" . $gender . " " . ucfirst($name) . "</h3>,<br> <b>Thank You For Registered As a New Donor in Our System.</b><br><b>Now You Can Make an Appointment at Any Time !<b>";

				$mail_result = $mail_object->Send_Mail($email, $subject, $body);
			}
		} else {
			////////// If method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
?>
		<script>
			window.location = "../view/donor_reg.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>

	<?php

		break;

	case 'update_donor':

		////////// Get Required Variables From update Donor Form //////////
		$donor_id = $_REQUEST['donor_id'];
		$name     = trim($_POST['name']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$dob      = $_POST['dob'];
		$gender   = $_POST['gender'];
		$disc_id  = $_POST['disc_id'];
		$email    = trim($_POST['email']);
		$blood_grp = $_POST['blood_grp'];
		$otherInfo = trim($_POST['otherInfo']);

		////////// Execute update_donor method //////////
		$result = $donor_object->update_donor($donor_id, $name, $nic, $gender, $dob, $con_no, $disc_id, $email, $blood_grp, $otherInfo);

		////////// If Successfully updated //////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
		$donor_id = base64_encode($donor_id);

	?>

		<script>
			window.location = "../view/display_donor.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&donor_id=<?php echo $donor_id; ?>";
		</script>

	<?php

		break;

	case 'donor_searchByNIC':

		////////// Get donor NIC from ajax request //////////
		$nic = $_POST['nic'];

		////////// Get donor data by NIC //////////
		$result = $donor_object->Donor_searchByNIC($nic);

		////////// If result has at least 1 record //////////
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$array = $row;
		} else {
			////////// If result has no records //////////
			$array = array("fail");
		}

		////////// json encode response data //////////
		echo json_encode($array);

		break;

	case 'donor_searchByID':

		////////// Get donor ID from ajax request //////////
		$donor_id = $_POST['donor_id'];

		////////// Get donor data by donor ID //////////
		$result = $donor_object->Donor_searchByID($donor_id);

		////////// If result has at least 1 record //////////
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();

			$array = $row;
		} else {
			////////// If result has no records //////////
			$array = array("fail");
		}

		////////// json encode response data //////////
		echo json_encode($array);

		break;

	case 'active_block':

		////////// Get required variables from view page //////////
		$donor_id     = $_REQUEST['donor_id'];
		$donor_status  = $_REQUEST['donor_status'];
		$switch_status = ($donor_status == 1) ? 0 : 1;

		////////// Change donor status //////////
		$result = $donor_object->active_block($donor_id, $switch_status);

		////////// If Successfully updated //////////
		if ($result) {
			$msg = "Change Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_donors.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'removeDon':

		$donor_id     = $_REQUEST['donor_id'];

		////////// Execute remove donor method //////////
		$result = $donor_object->remove_donor($donor_id);

		////////// If Successfully removed //////////
		if ($result) {
			$msg = "Remove Donor Successfully";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_donors.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}
?>