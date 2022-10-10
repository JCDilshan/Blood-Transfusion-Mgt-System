<?php
///////////////////////// Include camp-staff referal model /////////////////////////
include('../model/campRefers_model.php');
$doc_campObj = new Doctor_camp($conn);
$nurse_campObj = new Nurse_camp($conn);
$mem_campObj = new Member_camp($conn);

///////////////////////// Include camp model /////////////////////////
include('../model/camp_model.php');
$camp_object = new Camp($conn);

///////////////////////// Include staff model /////////////////////////
include('../model/staff_model.php');
$doctor_object = new Doctor($conn);
$nurse_object  = new Nurse($conn);
$member_object = new Other_Staff($conn);

///////////////////////// Include donor model /////////////////////////
include('../model/donor_model.php');
$donor_object = new Donor($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'add_camp':

		///////////////////////// Get form variable values /////////////////////////
		$camp_id    = $_POST['camp_id'];
		$org_name   = trim($_POST['org_name']);
		$location   = trim($_POST['location']);
		$disc_id    = $_POST['disc_id'];
		$date       = $_POST['date'];
		$time       = $_POST['time'];
		$tar_donors = $_POST['tar_donors'];
		$other_info = trim($_POST['other_info']);

		$doc_id = $_POST['doc'];
		$nurse_id = $_POST['nurse'];
		$mem_id = $_POST['mem'];

		///////////////////////// Execute add new camp method /////////////////////////
		$main_result   = $camp_object->add_camp($org_name, $location, $disc_id, $date, $time, $tar_donors, $other_info);

		///////////////////////// Insert assigned doctors /////////////////////////
		if (is_array($doc_id)) {
			for ($i = 0; $i <= count($doc_id) - 1; $i++) {
				$refers = $doc_campObj->add_doc($doc_id[$i], $camp_id);
			}
		}

		///////////////////////// Insert assigned nurse /////////////////////////
		if (is_array($nurse_id)) {
			for ($j = 0; $j <= count($nurse_id) - 1; $j++) {
				$refers_2 = $nurse_campObj->add_nurse($nurse_id[$j], $camp_id);
			}
		}

		///////////////////////// Insert assigned helping staff /////////////////////////
		if (is_array($mem_id)) {
			for ($k = 0; $k <= count($mem_id) - 1; $k++) {
				$refers_3 = $mem_campObj->add_mem($mem_id[$k], $camp_id);
			}
		}

		///////////////////////// If process is successed /////////////////////////
		if ($main_result) {

			///////////////////////// Includes mail configurations /////////////////////////
			include_once('../controller/mail_controller.php');
			$mail_object = new mail_send();
			$subject = "Notice Of a New Blood Camp";


			///////////////////////// Notify the camp by an email to the donors in the relevant district /////////////////////////
			$donor_result = $donor_object->get_DonorByDistrictID($disc_id);
			while ($donor_row = $donor_result->fetch_assoc()) {

				$body = "Mr./Mrs. " . $donor_row['donor_name'] . ", A New Blood  Camp Will Be Held Near You !.If You Can, We Kindly Ask You To Attend.<br><br><h4><u>camp details</h4></u><br> <b>Camp ID :- </b>" . $camp_id . "<br><b>Venue :- </b>" . $location . "<br><b>Date :- </b>" . $date . "<br><b>Start Time :- </b>" . $time . "<br>" . strtoupper("thank you ! have a good day !");

				$mail_result = $mail_object->Send_Mail($donor_row['donor_email'], $subject, $body);
			}

			$msg = "Successfully Added New Blood Camp";
			$res_status = '1';
		} else {
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		///////////////////////// Encode response messages /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

		///////////////////////// Redirect to the view page /////////////////////////
?>
		<script>
			window.location = "../view/add_blood_camp.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php
		break;

	case 'search_camp':

		///////////////////////// Get ajax variable values /////////////////////////
		$camp_id = $_POST['camp_id'];

		///////////////////////// Get camp info by camp ID /////////////////////////
		$result = $camp_object->get_specificCamp($camp_id);

		///////////////////////// If has records /////////////////////////
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			echo $row['date'];
		}

		break;

	case 'editCamp_getdoctors':

		///////////////////////// Get ajax variable values /////////////////////////
		$camp_id = $_POST['camp_id'];

		///////////////////////// Get camp assigned doctors /////////////////////////
		$result_2 = $doc_campObj->get_doc($camp_id);
		$curr_docs_array = array();
		$count = 0;

		if ($result_2) {
			while ($curr_docs = $result_2->fetch_assoc()) {

				///////////////////////// Make array for doctors info /////////////////////////
				$curr_docs_array[$count][0] = $curr_docs['doc_id'];
				$curr_docs_array[$count][1] = $curr_docs['fname'] . " " . $curr_docs['lname'];
				$count++;
			}
		} else {
			$curr_docs_array = array("NoData");
		}

		///////////////////////// encode data to json format /////////////////////////
		echo json_encode($curr_docs_array);

		break;

	case 'editCamp_getnurse':

		///////////////////////// Get ajax variable values /////////////////////////
		$camp_id = $_POST['camp_id'];

		///////////////////////// Get camp assigned nurse /////////////////////////
		$result_2 = $nurse_campObj->get_nurse($camp_id);
		$curr_nurse_array = array();
		$count = 0;

		if ($result_2) {
			while ($curr_nurse = $result_2->fetch_assoc()) {

				///////////////////////// Make array for nurse info /////////////////////////
				$curr_nurse_array[$count][0] = $curr_nurse['nurse_id'];
				$curr_nurse_array[$count][1] = $curr_nurse['fname'] . " " . $curr_nurse['lname'];
				$count++;
			}
		} else {
			$curr_nurse_array = array("NoData");
		}

		///////////////////////// encode data to json format /////////////////////////
		echo json_encode($curr_nurse_array);

		break;

	case 'editCamp_getmem':

		///////////////////////// Get ajax variable values /////////////////////////
		$camp_id = $_POST['camp_id'];

		///////////////////////// Get camp assigned helping members /////////////////////////
		$result_2 = $mem_campObj->get_mem($camp_id);
		$curr_mem_array = array();
		$count = 0;

		if ($result_2) {
			while ($curr_mem = $result_2->fetch_assoc()) {

				///////////////////////// Make array for member info /////////////////////////
				$curr_mem_array[$count][0] = $curr_mem['mem_id'];
				$curr_mem_array[$count][1] = $curr_mem['fname'] . " " . $curr_mem['lname'];
				$count++;
			}
		} else {
			$curr_mem_array = array("NoData");
		}

		///////////////////////// encode data to json format /////////////////////////
		echo json_encode($curr_mem_array);

		break;

	case 'set_held':

		///////////////////////// Get form variable values /////////////////////////
		$camp_id = $_REQUEST['camp_id'];

		///////////////////////// Set camp status as held /////////////////////////
		$main_result = $camp_object->set_held($camp_id);

		if ($main_result) {

			///////////////////////// Also set camp-referels status as held /////////////////////////
			$doc_campObj->set_held($camp_id);
			$nurse_campObj->set_held($camp_id);
			$mem_campObj->set_held($camp_id);

			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		///////////////////////// Encode response messages /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

		///////////////////////// Redirect to the view page /////////////////////////
	?>
		<script>
			window.location = "../view/mng_camps.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php
		break;

	case 'finalize':

		///////////////////////// Get form variable values /////////////////////////
		$camp_id = $_REQUEST['camp_id'];
		$par_donors = $_POST['par_donors'];
		$remark = $_POST["remark"];

		///////////////////////// Insert participated donors count /////////////////////////
		$result = $camp_object->camp_finalize($camp_id, $par_donors, $remark);

		///////////////////////// Redirect to the view page /////////////////////////
	?>
		<script>
			window.location = "../view/mng_camps.php";
		</script>
	<?php
		break;

	case 'edit_camp':

		///////////////////////// Get Camp ID /////////////////////////
		$camp_id = $_POST['camp_id'];

		///////////////////////// Get relevant camp details /////////////////////////
		$get_camp = $camp_object->get_specificCamp($camp_id);
		$camp_array = $get_camp->fetch_assoc();

		///////////////////////// Get edited form variable values /////////////////////////
		$org_name   = trim($_POST['org_name']);
		$location   = trim($_POST['location']);
		$date       = $_POST['date'];
		$time       = $_POST['time'];
		$tar_donors = $_POST['tar_donors'];
		$other_info = trim($_POST['other_info']);
		$editStmt   = $_POST['editStmt'];

		$doc_id = $_POST['doc'];
		$nurse_id = $_POST['nurse'];
		$mem_id = $_POST['mem'];

		///////////////////////// Check if location, date or time has changed /////////////////////////
		if (($camp_array['location'] != $location || $camp_array['date'] != $date) || $camp_array['start_time'] != $time) {
			$stmt = true;
		} else {
			$stmt = false;
		}

		///////////////////////// Execute edit camp method /////////////////////////
		$main_result   = $camp_object->edit_camp($camp_id, $org_name, $location, $date, $time, $tar_donors, $other_info);

		///////////////////////// Check if assigned staffs has changed /////////////////////////
		if ($editStmt == 1) {

			///////////////////////// Remove all existing doctors and staff members /////////////////////////
			$doc_campObj->remove_docs($camp_id);
			$nurse_campObj->remove_nurse($camp_id);
			$mem_campObj->remove_mem($camp_id);

			///////////////////////// Assign again new doctors and staff members /////////////////////////
			if (is_array($doc_id)) {
				for ($i = 0; $i <= count($doc_id) - 1; $i++) {
					$refers = $doc_campObj->add_doc($doc_id[$i], $camp_id);
				}
			}

			if (is_array($nurse_id)) {
				for ($j = 0; $j <= count($nurse_id) - 1; $j++) {
					$refers_2 = $nurse_campObj->add_nurse($nurse_id[$j], $camp_id);
				}
			}

			if (is_array($mem_id)) {
				for ($k = 0; $k <= count($mem_id) - 1; $k++) {
					$refers_3 = $mem_campObj->add_mem($mem_id[$k], $camp_id);
				}
			}
		}

		///////////////////////// If main method is success /////////////////////////
		if ($main_result) {

			///////////////////////// If location, date or time has changed /////////////////////////
			if ($stmt == true) {

				///////////////////////// Inform new changes to the relevant donors via email /////////////////////////
				include_once('../controller/mail_controller.php');
				$mail_object = new mail_send();
				$subject = "Notice About Changing The Details Of The Blood Camp";

				$donor_result2 = $donor_object->get_DonorByDistrictID($camp_array['district_id']);
				while ($donor_row2 = $donor_result2->fetch_assoc()) {

					$body = "Mr./Mrs. " . $donor_row2['donor_name'] . ", We Kindly Inform You That The Details Of The Blood Camp That You Were Informed About Have Been Changed As Follows...<br><br><h4><u>Updated Details</h4></u><br> <b>Camp ID :- </b>" . $camp_id . "<br><b>Venue :- </b>" . $location . "<br><b>Date :- </b>" . $date . "<br><b>Start Time :- </b>" . $time . "<br> <h4>Thank You For Your Attention On This</h4>" . strtoupper("have a good day !");

					$mail_result = $mail_object->Send_Mail($donor_row2['donor_email'], $subject, $body);
				}
			}

			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		///////////////////////// Encode response messages /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

		///////////////////////// Redirect to the view page /////////////////////////
	?>
		<script>
			window.location = "../view/edit_campDetails.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>&camp_id=<?php echo base64_encode($camp_id); ?>";
		</script>
	<?php

		break;

	case 'remove_camp':

		///////////////////////// Get form variable values /////////////////////////
		$camp_id = $_REQUEST['camp_id'];

		///////////////////////// Execute remove camp method /////////////////////////
		$result = $camp_object->remove_camp($camp_id);

		///////////////////////// If main is success /////////////////////////
		if ($result) {
			$msg = "Successfully Removed Camp";
			$res_status = '1';
		} else {
			///////////////////////// If main method is fail /////////////////////////
			$msg = "oops... something went wrong (This data may be used for an ongoing process)<br> please try again later";
			$res_status = '0';
		}

		///////////////////////// Encode response messages /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

		///////////////////////// Redirect to the view page /////////////////////////
	?>
		<script>
			window.location = "../view/mng_camps.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php
		break;
}
?>