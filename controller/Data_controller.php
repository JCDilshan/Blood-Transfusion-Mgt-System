<?php

///////////////////////// Switch request status /////////////////////////
date_default_timezone_set("Asia/Colombo");

///////////////////////// Include boold and inventory model /////////////////////////
include_once('../model/blood_model.php');
include_once('../model/inventory_model.php');

$RawBlood_obj = new Raw_Bloods($conn);
$Inventory_obj = new Inventory($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'get_RawBloodData':

		///////////////////////// Execute get donation history method /////////////////////////
		$result = $RawBlood_obj->get_Donations();

		///////////////////////// If current donation method success /////////////////////////
		if ($result) {
			///////////////////////// Make empty array as $RawTotal_array /////////////////////////
			$RawTotal_array = array();
			$curr_year = date("Y");

			///////////////////////// Define arrays to insert monthly data for last 5 years /////////////////////////
			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				${'Rarray' . $i} = array("", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}

			///////////////////////// Fetch data /////////////////////////
			while ($row = $result->fetch_assoc()) {
				///////////////////////// Insert donation bags count into the array /////////////////////////
				${'Rarray' . $row['don_year']}[$row['don_month']] = $row['don_count'];
			}

			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				///////////////////////// Push cretaed arrays into the $RawTotal array /////////////////////////
				array_push($RawTotal_array, ${'Rarray' . $i});
			}
		} else {
			///////////////////////// If process has error /////////////////////////
			$RawTotal_array = array("Error");
		}

		///////////////////////// Execute get accepted donation method /////////////////////////
		$result_2 = $RawBlood_obj->get_Accepted();

		///////////////////////// If get accepted donations method success /////////////////////////
		if ($result_2) {
			///////////////////////// Make empty array as $Accepted_array /////////////////////////
			$Accepted_array = array();
			$curr_year = date("Y");

			///////////////////////// Define arrays to insert monthly data for last 5 years /////////////////////////
			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				${'Accarray' . $i} = array("", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}

			///////////////////////// Fetch data /////////////////////////
			while ($row_2 = $result_2->fetch_assoc()) {
				///////////////////////// Insert accepted bags count into the array /////////////////////////
				${'Accarray' . $row_2['chk_year']}[$row_2['chk_month']] = $row_2['chk_count'];
			}

			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				///////////////////////// Push cretaed arrays into the $Accepted array /////////////////////////
				array_push($Accepted_array, ${'Accarray' . $i});
			}
		} else {
			///////////////////////// If process has error /////////////////////////
			$Accepted_array = array("Error");
		}

		///////////////////////// Execute get rejected donation method /////////////////////////
		$result_3 = $RawBlood_obj->get_Rejected();

		///////////////////////// If get rejected donations method success /////////////////////////
		if ($result_3) {
			///////////////////////// Make empty array as $Rejected_array /////////////////////////
			$Rejected_array = array();
			$curr_year = date("Y");

			///////////////////////// Define arrays to insert monthly data for last 5 years /////////////////////////
			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				${'Rejarray' . $i} = array("", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}

			///////////////////////// Fetch data /////////////////////////
			while ($row_3 = $result_3->fetch_assoc()) {
				///////////////////////// Insert rejected bags count into the array /////////////////////////
				${'Rejarray' . $row_3['chk_year']}[$row_3['chk_month']] = $row_3['chk_count'];
			}

			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				///////////////////////// Push cretaed arrays into the $Rejected array /////////////////////////
				array_push($Rejected_array, ${'Rejarray' . $i});
			}
		} else {
			///////////////////////// If process has error /////////////////////////
			$Rejected_array = array("Error");
		}

		///////////////////////// Create another array as a main array for pass all arrays together /////////////////////////
		$main_array = array($RawTotal_array, $Accepted_array, $Rejected_array);

		///////////////////////// Encode json format and send data /////////////////////////
		echo json_encode($main_array);

		break;

	case 'get_InventoryData':

		///////////////////////// Execute get added blood bags method /////////////////////////
		$result = $Inventory_obj->get_AddedBlood();

		///////////////////////// If get added blood bags method success /////////////////////////
		if ($result) {
			///////////////////////// Make empty array as $Added_array /////////////////////////
			$Added_array = array();
			$curr_year = date("Y");

			///////////////////////// Define arrays to insert monthly data for last 5 years /////////////////////////
			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				${'Addarray' . $i} = array("", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}

			///////////////////////// Fetch data /////////////////////////
			while ($row = $result->fetch_assoc()) {
				///////////////////////// Insert added bags count into the array /////////////////////////
				${'Addarray' . $row['add_year']}[$row['add_month']] = $row['add_count'];
			}

			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				///////////////////////// Push cretaed arrays into the $Added array /////////////////////////
				array_push($Added_array, ${'Addarray' . $i});
			}
		} else {
			///////////////////////// If process has error /////////////////////////
			$Added_array = array("Error");
		}

		///////////////////////// Execute get issued blood bags method /////////////////////////
		$result_2 = $Inventory_obj->get_IssuedBlood();

		///////////////////////// If get issued blood bags method success /////////////////////////
		if ($result_2) {
			///////////////////////// Make empty array as $Issued_array /////////////////////////
			$Issued_array = array();
			$curr_year = date("Y");

			///////////////////////// Define arrays to insert monthly data for last 5 years /////////////////////////
			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				${'Issuearray' . $i} = array("", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}

			///////////////////////// Fetch data /////////////////////////
			while ($row_2 = $result_2->fetch_assoc()) {
				///////////////////////// Insert issued bags count into the array /////////////////////////
				${'Issuearray' . $row_2['issue_year']}[$row_2['issue_month']] = $row_2['issue_count'];
			}

			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				///////////////////////// Push cretaed arrays into the $Issued array /////////////////////////
				array_push($Issued_array, ${'Issuearray' . $i});
			}
		} else {
			///////////////////////// If process has error /////////////////////////
			$Issued_array = array("Error");
		}


		///////////////////////// Execute get expired blood bags method /////////////////////////
		$result_3 = $Inventory_obj->get_ExpiredBlood();

		///////////////////////// If get expired blood bags method success /////////////////////////
		if ($result_3) {
			///////////////////////// Make empty array as $Expired_array /////////////////////////
			$Expired_array = array();
			$curr_year = date("Y");

			///////////////////////// Define arrays to insert monthly data for last 5 years /////////////////////////
			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				${'Exparray' . $i} = array("", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			}

			///////////////////////// Fetch data /////////////////////////
			while ($row_3 = $result_3->fetch_assoc()) {
				///////////////////////// Insert expired bags count into the array /////////////////////////
				${'Exparray' . $row_3['exp_year']}[$row_3['exp_month']] = $row_3['exp_count'];
			}

			for ($i = $curr_year - 5; $i <= $curr_year; $i++) {
				///////////////////////// Push cretaed arrays into the $Expired array /////////////////////////
				array_push($Expired_array, ${'Exparray' . $i});
			}
		} else {
			///////////////////////// If process has error /////////////////////////
			$Expired_array = array("Error");
		}

		///////////////////////// Create another array as a main array for pass all arrays together /////////////////////////
		$main_array = array($Added_array, $Issued_array, $Expired_array);

		///////////////////////// Encode json format and send data /////////////////////////
		echo json_encode($main_array);

		break;

	case 'get_CurrentInvenData':

		///////////////////////// Execute get current inventory data method /////////////////////////
		$result = $Inventory_obj->get_CurrentInven();

		///////////////////////// Make a new array to define blood groups and set default values as 0 /////////////////////////
		$array = array(array("A+", 0), array("A-", 0), array("B+", 0), array("B-", 0), array("O+", 0), array("O-", 0), array("AB+", 0), array("AB-", 0));
		$count = 0;

		///////////////////////// Fetch data untill complete current inventory records /////////////////////////
		while ($row = $result->fetch_assoc()) {
			for ($i = 0; $i < count($array); $i++) {
				///////////////////////// If reading record's blood group match to the blood group that include in array /////////////////////////
				if ($array[$i][0] == $row['grp_name']) {
					///////////////////////// Set total bags count /////////////////////////
					$array[$i][1] = $row['blcount'];
				}
			}
			$count++;
		}

		///////////////////////// Encode json format and send data /////////////////////////
		echo json_encode($array);

		break;

	case 'get_CurrentRawBloodData':

		///////////////////////// Execute get current raw blood data method /////////////////////////
		$result = $RawBlood_obj->get_CurrentBloods();

		///////////////////////// Make an empty array for insert raw blood data /////////////////////////
		$blood_array = array();

		$count = 0;

		///////////////////////// Fetch data untill complete current raw blood records /////////////////////////
		while ($row = $result->fetch_assoc()) {

			///////////////////////// Insert raw blood group name and corresponding bags count into the array /////////////////////////
			$blood_array[$count][0] = $row['grp_name'];
			$blood_array[$count][1] = $row['don_count'];

			$count++;
		}

		///////////////////////// Encode json format and send data /////////////////////////
		echo json_encode($blood_array);

		break;
}
