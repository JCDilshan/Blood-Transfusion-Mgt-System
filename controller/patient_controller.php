<?php

//////////////////////// Include patient model /////////////////////////
include('../model/patient_model.php');
$patient_object = new Patient($conn);
$bloodReq_obj = new Blood_Request($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'patient_search':

		///////// Get patient NIC from ajax request //////////
		$nic = trim($_POST['nic']);

		////////// Get patient details by NIC //////////
		$result = $patient_object->patient_search($nic);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			////////// Fetch data //////////
			$row = $result->fetch_assoc();
			$pat_array = array($row['patient_name'], $row['gender'], $row['age'], $row['blood_grp']);
		} else {
			////////// If result has no records //////////
			$pat_array = array("NoData");
		}

		////////// Json encode and pass data //////////
		echo json_encode($pat_array);

		break;

	case 'update_patient':

		///////// Get Required Variables From update patient Form //////////
		$patient_id = $_REQUEST['patient_id'];
		$name     = trim($_POST['name']);
		$nic      = trim($_POST['nic']);
		$age      = $_POST['age'];
		$gender   = $_POST['gender'];
		$blood_grp = $_POST['blood_grp'];

		///////// Execute update patient method //////////
		$result = $patient_object->update_patient($patient_id, $name, $nic, $gender, $age, $blood_grp);

		////////// If Method success //////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			////////// If Method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
		$patient_id = base64_encode($patient_id);

?>

		<script>
			window.location = "../view/display_patient.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&patient_id=<?php echo $patient_id; ?>";
		</script>

	<?php

		break;

	case 'removePat':

		////////// Get patient ID from view page //////////
		$patient_id     = $_REQUEST['patient_id'];

		////////// Execute remove patient method //////////
		$result = $patient_object->remove_patient($patient_id);

		////////// If Method success //////////
		if ($result) {
			$msg = "Removed Patient Successfully";
			$res_status = '1';
		} else {
			////////// If Method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_patients.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'add_request':

		///////// Get Required Variables From blood request Form //////////
		$prior = $_POST['reqtype'];
		$diagn = $_POST['diagnosis'];
		$name  = $_POST['pat_name'];
		$trans_his = $_POST['TH'];
		$nic   = trim($_POST['nic']);
		$trans_when = isset($_POST['when']) ? $_POST['when'] : "NULL";
		$age   = $_POST['pat_age'];
		$symptom = isset($_POST['react_symptom']) ? $_POST['react_symptom'] : "NULL";
		$gender = $_POST['gender'];
		$hb_lvl = $_POST['hb_level'];
		$weight = $_POST['weight'];
		$hb_date = $_POST['hbTest_date'];
		$bht = $_POST['bht'];
		$indic_proced = $_POST['indic_procedure'];
		$ward = $_POST['ward'];
		$indic_date = $_POST['indic_date'];
		$hosp = $_POST['hosp'];
		$req_amount = $_POST['req_amount'];
		$blood_grp = $_POST['blood_grp'];
		$user_id = $_POST['user_id'];
		$mblood_grp = ($_POST['mblood_grp'] != 0) ? $_POST['mblood_grp'] : "NULL";

		///////// Get patient details by patient NIC //////////
		$pat_result = $patient_object->patient_search($nic);

		////////// If patient exist //////////
		if ($pat_result->num_rows > 0) {
			///////// Fetch data //////////
			$pat_row = $pat_result->fetch_assoc();
			///////// Execute update patient method //////////
			$result = $patient_object->update_patient($pat_row['patient_id'], $name, $nic, $gender, $age, $blood_grp);
		} else {
			$result = $patient_object->add_patient($name, $nic, $gender, $age, $blood_grp);
		}

		////////// If add patient  success //////////
		if ($result) {

			///////// Execute add blood request method //////////
			$result_2 = $bloodReq_obj->add_request($prior, $name, $age, $gender, $weight, $nic, $bht, $ward, $hosp, $blood_grp, $mblood_grp, $diagn, $trans_his, $trans_when, $symptom, $hb_lvl, $hb_date, $indic_proced, $indic_date, $req_amount, $user_id);

			////////// If add request success //////////
			if ($result_2) {
				$msg = "Request Sent !";
				$res_status = '1';
			} else {
				////////// If Method fail //////////
				$msg = "oops... something went wrong please try again later";
				$res_status = '0';
			}
		} else {
			////////// If Method fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>

		<script>
			window.location = "../view/blood_request.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>

	<?php

		break;

	case 'edit_ReqAmount':

		///////// Get Required Variables From ajax request //////////
		$req_id = $_POST['req_id'];
		$amount = $_POST['amount'];

		///////// Execute change request blood bag amount method //////////
		$result = $bloodReq_obj->change_ReqAmount($req_id, $amount);

		////////// If Method success //////////
		if ($result) {
			echo "Changed Require Blood Amount";
		} else {
			////////// If Method fail //////////
			echo "Something Went Wrong..Task Fail !";
		}

		break;

	case 'req_reject':

		///////// Get Required Variables From view page //////////
		$req_id = $_REQUEST['req_id'];
		$req_reason = $_POST['req_reason'];
		$user_id = $_POST['user_id'];

		///////// Execute request reject method //////////
		$result = $bloodReq_obj->request_reject($req_id, $req_reason, $user_id);

		////////// If Method success //////////
		if ($result) {
			///////// Set request status to checked //////////
			$bloodReq_obj->set_checked($req_id);
			$msg = "Request Rejected !";
			$res_status = '1';
		} else {
			////////// If Method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/pending_blood_request.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'req_approval':

		///////// Get Required Variables From blood request approval form //////////
		$req_id = $_REQUEST['req_id'];
		$grp = $_POST['blood_grp'];
		$anti_a = $_POST['anti_a'];
		$s1_37 = isset($_POST['s1_37']) ? $_POST['s1_37'] : "NULL";
		$anti_ab = $_POST['anti_ab'];
		$s2_37 = isset($_POST['s2_37']) ? $_POST['s2_37'] : "NULL";
		$anti_b = $_POST['anti_b'];
		$s1_iat = isset($_POST['s1_iat']) ? $_POST['s1_iat'] : "NULL";
		$anti_d = $_POST['anti_d'];
		$s2_iat = isset($_POST['s2_iat']) ? $_POST['s2_iat'] : "NULL";
		$cell_a = $_POST['cell_a'];
		$user_id = $_POST['user_id'];
		$cell_b = $_POST['cell_b'];
		$comp_type = $_POST['comp_type'];
		$cell_o = $_POST['cell_o'];

		///////// Execute request approval method //////////
		$result = $bloodReq_obj->request_approval($req_id, $anti_a, $anti_ab, $anti_b, $anti_d, $cell_a, $cell_b, $cell_o, $grp, $s1_37, $s2_37, $s1_iat, $s2_iat, $user_id);

		////////// If Method success //////////
		if ($result) {

			///////// Set blood component type //////////
			$bloodReq_obj->set_compType($req_id, $comp_type);
			///////// Set request checked status //////////
			$bloodReq_obj->set_checked($req_id);

			$msg = "Request Approved And Passed To Inventory";
			$res_status = '1';
		} else {
			////////// If Method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/pending_blood_request.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'remove_CheckedReq':

		///////// Get request form ID from view page //////////
		$req_id = $_REQUEST['req_id'];

		///////// Execute remove request method //////////
		$result = $bloodReq_obj->remove_request($req_id);

		////////// If Method success //////////
		if ($result) {
			$msg = "Removed Request";
			$res_status = '1';
		} else {
			////////// If Method fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/Checked_Requests.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}
?>