<?php
///////////////////////// Include staff model /////////////////////////
include('../model/staff_model.php');

$doctor_object = new Doctor($conn);
$nurse_object  = new Nurse($conn);
$other_staff_obj = new Other_Staff($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'add_doctor':

		////////// Get required variables from add doctor form //////////
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$gender   = $_POST['gender'];
		$email = isset($_POST['email']) ? $_POST['email'] : "NULL";
		$qualif = trim($_POST['qualif']);

		////////// Execute Add Doctor Method //////////
		$result = $doctor_object->add_doctor($fname, $lname, $nic, $con_no, $gender, $email, $qualif);

		////////// If method success //////////
		if ($result) {
			$msg = "Successfully Added Doctor " . $fname . " " . $lname . "";
			$res_status = '1';
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
			window.location = "../view/doctors.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php
		break;

	case 'doctor_search':

		////////// Get doctor NIC from ajax request //////////
		$nic = $_POST['nic'];

		////////// Get doctor details by NIC //////////
		$result = $doctor_object->doctor_searchByNIC($nic);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'unreserved_doctors':

		////////// Get selected date from ajax request //////////
		$date = $_POST['date'];

		////////// Get unreserved doctors by selected date //////////
		$result = $doctor_object->unreserved_doctors($date);
		$output = "";

		////////// If method success //////////
		if ($result) {

			while ($row_1 = $result[0]->fetch_assoc()) {
				$output .= "<option value='" . $row_1['doc_id'] . "'>" . $row_1['fname'] . " " . $row_1['lname'] . "</option>";
			}
			while ($row_2 = $result[1]->fetch_assoc()) {
				$output .= "<option value='" . $row_2['doc_id'] . "'>" . $row_2['fname'] . " " . $row_2['lname'] . "</option>";
			}
		}

		echo $output;

		break;

	case 'update_doctor':

		////////// Get Required Variables From update doctor Form //////////
		$doc_id   = $_REQUEST['doc_id'];
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$gender   = $_POST['gender'];
		$email = isset($_POST['email']) ? $_POST['email'] : "NULL";
		$qualif = trim($_POST['qualif']);

		////////// Execute update doctor Method //////////
		$result = $doctor_object->update_doctor($doc_id, $fname, $lname, $nic, $con_no, $gender, $email, $qualif);

		////////// If method success //////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
		$doc_id = base64_encode($doc_id);

	?>
		<script>
			window.location = "../view/display_doctor.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&doc_id=<?php echo $doc_id; ?>";
		</script>
	<?php
		break;

	case 'active_blockDoc':

		////////// Get doctor ID and current status from view page //////////
		$doc_id        = $_REQUEST['doc_id'];
		$doc_status    = $_REQUEST['doc_status'];
		$switch_status = ($doc_status == 1) ? 0 : 1;

		////////// Execute change doctor status method //////////
		$result = $doctor_object->active_blockDoc($doc_id, $switch_status);

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
			window.location = "../view/doctors.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'removeDoc':

		////////// Get doctor ID from view page //////////
		$doc_id  = $_REQUEST['doc_id'];

		////////// Execute remove doctor method //////////
		$result = $doctor_object->remove_doctor($doc_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Removed Doctor Successfully";
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
			window.location = "../view/doctors.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'add_nurse':

		////////// Get Required Variables From Add nurse Form //////////
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$gender   = $_POST['gender'];
		$email = isset($_POST['email']) ? $_POST['email'] : "NULL";
		$qualif = trim($_POST['qualif']);

		////////// Execute Add nurse Method //////////
		$result = $nurse_object->add_nurse($fname, $lname, $nic, $con_no, $gender, $email, $qualif);

		////////// If method success //////////
		if ($result) {
			$msg = "Successfully Added Nurse " . $fname . " " . $lname . "";
			$res_status = '1';
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
			window.location = "../view/nurse.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php
		break;

	case 'nurse_search':

		////////// Get nurse NIC from ajax request //////////
		$nic = $_POST['nic'];

		////////// Get nurse details by NIC //////////
		$result = $nurse_object->nurse_searchByNIC($nic);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'unreserved_nurse':

		////////// Get selected date from ajax request //////////
		$date = $_POST['date'];

		////////// Get unreserved nurse by selected date //////////
		$result = $nurse_object->unreserved_nurse($date);
		$output = "";

		////////// If method success //////////
		if ($result) {

			while ($row_1 = $result[0]->fetch_assoc()) {
				$output .= "<option value='" . $row_1['nurse_id'] . "'>" . $row_1['fname'] . " " . $row_1['lname'] . "</option>";
			}
			while ($row_2 = $result[1]->fetch_assoc()) {
				$output .= "<option value='" . $row_2['nurse_id'] . "'>" . $row_2['fname'] . " " . $row_2['lname'] . "</option>";
			}
		}

		echo $output;

		break;

	case 'update_nurse':

		////////// Get Required Variables From update nurse Form //////////
		$nurse_id = $_REQUEST['nurse_id'];
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$gender   = $_POST['gender'];
		$email = isset($_POST['email']) ? $_POST['email'] : "NULL";
		$qualif = trim($_POST['qualif']);

		////////// Execute update nurse Method //////////
		$result = $nurse_object->update_nurse($nurse_id, $fname, $lname, $nic, $con_no, $gender, $email, $qualif);

		////////// If method success //////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
		$nurse_id = base64_encode($nurse_id);

	?>
		<script>
			window.location = "../view/display_nurse.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&nurse_id=<?php echo $nurse_id; ?>";
		</script>
	<?php
		break;

	case 'active_blockNurse':

		////////// Get nurse ID and current status from view page //////////
		$nurse_id      = $_REQUEST['nurse_id'];
		$nurse_status  = $_REQUEST['nurse_status'];
		$switch_status = ($nurse_status == 1) ? 0 : 1;

		////////// Execute change nurse status method //////////
		$result = $nurse_object->active_blockNurse($nurse_id, $switch_status);

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
			window.location = "../view/nurse.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'removeNurse':

		////////// Get nurse ID from view page //////////
		$nurse_id  = $_REQUEST['nurse_id'];

		////////// Execute remove nurse method //////////
		$result = $nurse_object->remove_nurse($nurse_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Removed Nurse Successfully";
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
			window.location = "../view/nurse.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'add_member':

		////////// Get Required Variables From Add member Form //////////
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$gender   = $_POST['gender'];
		$email = isset($_POST['email']) ? $_POST['email'] : "NULL";
		$qualif = trim($_POST['qualif']);

		////////// Execute Add member Method //////////
		$result = $other_staff_obj->add_member($fname, $lname, $nic, $con_no, $gender, $email, $qualif);

		////////// If method success //////////
		if ($result) {
			$msg = "Successfully Added Member " . $fname . " " . $lname . "";
			$res_status = '1';
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
			window.location = "../view/other_staff.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php
		break;

	case 'mem_search':

		////////// Get member NIC from ajax request //////////
		$nic = $_POST['nic'];

		////////// Get member details by NIC //////////
		$result = $other_staff_obj->member_searchByNIC($nic);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'unreserved_mem':

		////////// Get selected date from ajax request //////////
		$date = $_POST['date'];

		////////// Get unreserved members by selected date //////////
		$result = $other_staff_obj->unreserved_members($date);
		$output = "";

		////////// If method success //////////
		if ($result) {

			while ($row_1 = $result[0]->fetch_assoc()) {
				$output .= "<option value='" . $row_1['mem_id'] . "'>" . $row_1['fname'] . " " . $row_1['lname'] . "</option>";
			}
			while ($row_2 = $result[1]->fetch_assoc()) {
				$output .= "<option value='" . $row_2['mem_id'] . "'>" . $row_2['fname'] . " " . $row_2['lname'] . "</option>";
			}
		}

		echo $output;

		break;

	case 'update_member':

		////////// Get Required Variables From update member Form //////////
		$mem_id = $_REQUEST['mem_id'];
		$fname    = trim($_POST['fname']);
		$lname    = trim($_POST['lname']);
		$con_no   = "0" . $_POST['con_no'];
		$nic      = trim($_POST['nic']);
		$gender   = $_POST['gender'];
		$email    = isset($_POST['email']) ? $_POST['email'] : "NULL";
		$qualif   = $_POST['qualif'];

		////////// Execute update member Method //////////
		$result = $other_staff_obj->update_member($mem_id, $fname, $lname, $nic, $con_no, $gender, $email, $qualif);

		////////// If method success //////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
		$mem_id = base64_encode($mem_id);

	?>
		<script>
			window.location = "../view/display_otherStaff.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&mem_id=<?php echo $mem_id; ?>";
		</script>
	<?php
		break;

	case 'active_blockMem':

		////////// Get member ID and current status from view page //////////
		$mem_id        = $_REQUEST['mem_id'];
		$mem_status    = $_REQUEST['mem_status'];
		$switch_status = ($mem_status == 1) ? 0 : 1;

		////////// Execute change member status method //////////
		$result = $other_staff_obj->active_blockMember($mem_id, $switch_status);

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
			window.location = "../view/other_staff.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'removeMem':

		////////// Get member ID from view page //////////
		$mem_id  = $_REQUEST['mem_id'];

		////////// Execute remove member method //////////
		$result = $other_staff_obj->remove_member($mem_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Removed Member Successfully";
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
			window.location = "../view/other_staff.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}
?>