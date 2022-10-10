<?php

///////////////////////// Include patient, donation and inventory model /////////////////////////
include('../model/patient_model.php');
$bloodReq_obj = new Blood_Request($conn);

include('../model/donation_model.php');
$TestedDonation_obj = new Donation($conn);

include('../model/inventory_model.php');
$inventory_obj = new Inventory($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'checkBag_exist':

		$bag_id = $_POST["bag_id"];

		////////// Get bag data from inventory //////////
		$result = $inventory_obj->get_bagDetails($bag_id);
		////////// Get bag data from tested bloods //////////
		$result_2 = $TestedDonation_obj->get_SpecificPassedRecord($bag_id);

		////////// If either result has at least 1 record //////////
		if ($result->num_rows > 0 || $result_2->num_rows > 0) {
			echo "HaveData";
		} else {
			////////// If both results have no data //////////
			echo "NoData";
		}

		break;

	case 'add_blood':

		////////// Get Required Variables From view page //////////
		$bag_id = $_POST['bag_id'];
		$blood_grp = $_POST['blood_grp'];
		$comp_type = $_POST['comp_type'];
		$sealed_date = $_POST['sealed_date'];
		$donor_id = $_POST['donor_id'];
		$camp_id  = ($_POST['camp_id'] != "") ? $_POST['camp_id'] : "NULL";
		$hosp_id = ($_POST['hosp_id'] != "") ? $_POST['hosp_id'] : "NULL";

		////////// Execute Add blood bag Method //////////
		$result = $inventory_obj->add_blood($bag_id, $blood_grp, $comp_type, $sealed_date, $donor_id, $camp_id, $hosp_id);

		////////// If add blood bag successfully //////////
		if ($result) {
			////////// Remove blood bag record from inventory pass table //////////
			$TestedDonation_obj->remove_PassRecord($bag_id);

			$msg = "Added Blood Bag Successfully";
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
			window.location = "../view/TestPass_BloodForms.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'active_blockByBag':

		////////// Get Required Variables From view page //////////
		$bag_id = $_REQUEST['bag_id'];
		$issue_stmt = $_REQUEST['issue_stmt'];
		$switch_status = ($issue_stmt == 1) ? 0 : 1;

		////////// Switch blood bag active status //////////
		$result = $inventory_obj->active_block($bag_id, $switch_status);

		////////// If method success //////////
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
			window.location = "../view/mng_inventory.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'quick_block':

		////////// Get Required Variables From view page //////////
		$category = $_POST['category'];
		$target   = $_POST['target'];

		////////// Execute blood bags quick block method //////////
		$result = $inventory_obj->quick_block($category, $target);

		////////// If method success //////////
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
			window.location = "../view/mng_inventory.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'get_bagDetails':

		////////// Get bag ID from ajax request //////////
		$bag_id = $_POST['bag_id'];

		$result = $inventory_obj->get_bagDetails($bag_id);

		////////// If method success //////////
		if ($result) {
			////////// Fetch data //////////
			$row = $result->fetch_assoc();
			$array = $row;
		} else {
			$array = array("");
		}

		////////// json encode and send response data //////////
		echo json_encode($array);

		break;

	case 'remove_bag':

		////////// Get bag ID from view page //////////
		$bag_id = $_REQUEST['bag_id'];

		////////// Execute remove blood bag from inventory method //////////
		$result = $inventory_obj->remove_bag($bag_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Removed Blood Bag Successfully";
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
			window.location = "../view/mng_inventory.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'get_MatchingBags':

		////////// Get Required Variables From Ajax request //////////
		$grp = $_POST['cm_grp'];
		$all_grps = implode(", ", $grp);

		$comp_type = $_POST['comp'];
		$req_amount = $_POST['req_amount'];

		$output = "";

		////////// Get cross matching blood bags from inventory //////////
		$result = $inventory_obj->get_MatchingBags($all_grps, $comp_type, $req_amount);

		////////// Get matching bags count ////////// 
		$match_count = $result->num_rows;
		$count = 1;

		////////// If matching bags count > 0 //////////
		if ($match_count > 0) {

			////////// Fetch data untill complete records //////////
			while ($row = $result->fetch_assoc()) {
				////////// Make readonly input fields for display issuable blood bags IDs //////////
				$output .= "<input name='issueBags[]' class='font-weight-bold' value='" . $row['bag_id'] . "' style='color:#000; font-size: 18px;' readonly> &nbsp;<span class='font-weight-bold text-danger'>(" . $row['grp_name'] . ")</span><br>";

				////////// If Issue bags count equal to request amount, then loop terminate //////////
				if ($count == $req_amount) {
					break;
				}
				$count++;
			}
		} else {
			////////// If matching bags count == 0 //////////
			$match_count = 0;
		}

		////////// json encode and send response data //////////
		echo json_encode(array($output, $match_count));

		break;

	case 'issue_blood':

		////////// Get Blood Request id, Patient id, Hospital and Bag ids from Issue Form //////////
		$request_id = $_REQUEST['request_id'];
		$pat_id = $_POST['pat_id'];
		$hosp   = $_POST['hosp'];
		$bag_id = $_POST['issueBags'];

		////////// Add Issued History //////////
		for ($i = 0; $i < count($bag_id); $i++) {
			$result = $inventory_obj->add_issueHistory($bag_id[$i], $pat_id, $hosp);

			////////// Set Issued Bags Status //////////
			if ($result) {
				$result_2 = $inventory_obj->set_issue($bag_id[$i]);
			}
		}

		////////// If Process Success //////////
		if ($result_2) {
			////////// Set Request Form's Status //////////
			$bloodReq_obj->set_issued($request_id);

			////////// Send notification if blood bags count drop below safe threshold ///////////
			include_once("../model/msg_notif_model.php");
			$notif_obj = new Notification($conn);

			$getRemGrpInfo = $inventory_obj->get_RemainGrpCount();

			while ($getRemGrpArray = $getRemGrpInfo->fetch_assoc()) {
				if ($getRemGrpArray["bag_count"] <= 5) {
					$notif_obj->createNotif_InventoryDecline($getRemGrpArray["grp_name"], $getRemGrpArray["bag_count"]);
				}
			}

			$msg = "Blood Issued Successfully";
			$res_status = '1';
		} else {
			////////// If Process Fail //////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		////////// Encode and Send Response Message //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);
	?>
		<script>
			window.location = "../view/Approved_Requests.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}
?>