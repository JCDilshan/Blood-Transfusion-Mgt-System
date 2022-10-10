<?php
///////////////////////// Include donor and donation model /////////////////////////
include('../model/donor_model.php');
$donor_obj = new Donor($conn);

include('../model/donation_model.php');
$donation_obj = new Donation($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'checkBag_exist':

		///////////////////////// Get data from ajax request /////////////////////////
		$bag_id = trim($_POST['bag_id']);

		///////////////////////// Execute get blood bag data by bag id method /////////////////////////
		$result = $donation_obj->checkBag_exist($bag_id);

		///////////////////////// If result has records /////////////////////////
		if ($result->num_rows > 0) {
			echo "HaveData";
		} else {
			echo "NoData";
		}

		break;

	case 'add_donation':

		///////////////////////// Get form data from view page /////////////////////////
		$donor_id  = $_POST['donor_id'];
		$camp_id   = ($_POST['camp_id'] != 0) ? $_POST['camp_id'] : "NULL";
		$hosp_id   = ($_POST['hosp_id'] != 0) ? $_POST['hosp_id'] : "NULL";
		$don_date  = $_POST['don_date'];
		$bag_id    = $_POST['bag_id'];

		///////////////////////// Execute add donation method /////////////////////////
		$result = $donation_obj->add_donation($bag_id, $donor_id, $camp_id, $hosp_id, $don_date);

		///////////////////////// If add donation success /////////////////////////
		if ($result) {
			///////////////////////// Add donation data into donation history table as well /////////////////////////
			$donation_obj->add_Historyrecord($bag_id, $donor_id, $camp_id, $hosp_id, $don_date);
			///////////////////////// Increase donor's total donated bag count /////////////////////////
			$donor_obj->donation_increase($donor_id);

			$msg = "Added New Donation";
			$res_status = '1';
		} else {
			///////////////////////// If add donation method fail /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
?>
		<script>
			window.location = "../view/add_donation.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'remove_donation':

		///////////////////////// Get data from view page /////////////////////////
		$bag_id    = $_REQUEST['bag_id'];
		$donation_id = $_REQUEST['donation_id'];
		$donor_id = $_REQUEST['donor_id'];

		///////////////////////// Execute remove donation method /////////////////////////
		$result = $donation_obj->remove_donation($donation_id);

		///////////////////////// If remove donation method success /////////////////////////
		if ($result) {
			///////////////////////// Decrease donor's total donated bag count  /////////////////////////
			$donor_obj->donation_decrease($donor_id);
			///////////////////////// Remove donation record from donation history as well /////////////////////////
			$donation_obj->remove_record($bag_id);
			$msg = "Donation Removed";
			$res_status = '1';
		} else {
			///////////////////////// If remove donation method fail /////////////////////////
			$msg = "Somthing went wrong..Please Try Again Later<br>Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>
		<script>
			window.location = "../view/pending_blood_donations.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;


	case 'take_proceed':

		///////////////////////// Get data from view page /////////////////////////
		$donation_id = $_REQUEST['donation_id'];

		///////////////////////// Set proceeding status to 1 /////////////////////////
		$result = $donation_obj->take_proceed($donation_id);

		///////////////////////// If method success /////////////////////////
		if ($result) {
			$msg = "Taken To Proceed !";
			$res_status = '1';
		} else {
			///////////////////////// If method fail /////////////////////////
			$msg = "Somthing went wrong..(May be this data is being used for an ongoing process)<br>Try again later";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>

		<script>
			window.location = "../view/pending_blood_donations.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;


	case 'proc_Rollback':

		///////////////////////// Get data from view page /////////////////////////
		$donation_id = $_REQUEST['donation_id'];

		///////////////////////// Set proceeding status back to 0 /////////////////////////
		$result = $donation_obj->rollback($donation_id);

		///////////////////////// If method success /////////////////////////
		if ($result) {
			$msg = "Rollback Successful";
			$res_status = '1';
		} else {
			///////////////////////// If method fail /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>
		<script>
			window.location = "../view/proceed_blood_donations.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;


	case 'inventory_pass':

		///////////////////////// Get form data from view page /////////////////////////
		$donor_id = $_POST['donor_id'];
		$camp_id  = ($_POST['camp_id'] != 0) ? $_POST['camp_id'] : "NULL";
		$hosp_id = ($_POST['hosp_id'] != 0) ? $_POST['hosp_id'] : "NULL";
		$bag_id   = $_POST['bag_id'];
		$blood_grp = $_POST['blood_grp'];
		$comp_type = $_POST['comp_type'];
		$sealed = $_POST['sealed_date'];

		///////////////////////// Execute blood bag pass to inventory method /////////////////////////
		$result = $donation_obj->Inventory_pass($bag_id, $donor_id, $camp_id, $hosp_id, $blood_grp, $comp_type, $sealed);

		///////////////////////// If method success /////////////////////////
		if ($result) {
			$msg = "Blood Bag Passed To Inventory!";
			$res_status = '1';
		} else {
			///////////////////////// If method fail /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>
		<script>
			window.location = "../view/bloodBag_inventoryPass.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;


	case 'checked_result':

		///////////////////////// Get form data from view page /////////////////////////
		$donation_id  = $_POST['donation_id'];
		$bag_id       = $_POST['bag_id'];
		$donor_id     = $_POST['donor_id'];
		$camp_id      = ($_POST['camp_id'] != NULL) ? $_POST['camp_id'] : "NULL";
		$hosp_id      = ($_POST['hosp_id'] != NULL) ? $_POST['hosp_id'] : "NULL";
		$check_status = $_POST['action'];
		$check_date   = $_POST['check_date'];
		$rej_reason   = isset($_POST['rej_reason']) ? trim($_POST['rej_reason']) : '';
		$rej_reason   = base64_encode($rej_reason);

		///////////////////////// Insert blood test passed record /////////////////////////
		$result = $donation_obj->add_TestedRecord($bag_id, $donor_id, $camp_id, $hosp_id, $check_status, $check_date, $rej_reason);

		///////////////////////// If method success /////////////////////////
		if ($result) {
			$donation_obj->remove_donation($donation_id);
			$msg = "Process Complete !";
			$res_status = '1';
		} else {
			///////////////////////// If method fail /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>
		<script>
			window.location = "../view/proceed_blood_donations.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;


	case 'remove_CheckResult':

		///////////////////////// Get data from view page /////////////////////////
		$record_id = $_REQUEST['record_id'];

		///////////////////////// Execute remove tested pass record method /////////////////////////
		$result = $donation_obj->remove_TestedRecord($record_id);

		///////////////////////// If method success /////////////////////////
		if ($result) {
			$msg = "Removed Record!";
			$res_status = '1';
		} else {
			///////////////////////// If method fail /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response data /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>
		<script>
			window.location = "../view/checked_blood_donations.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}
?>