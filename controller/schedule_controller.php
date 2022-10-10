<?php
///////////////////////// Include schedule, donation and donor models /////////////////////////
include('../model/schedule_model.php');
$hospital_object = new Hospital($conn);
$schedule_object = new Schedule($conn);
$appoint_object  = new Appointment($conn);

include('../model/donation_model.php');
$donHistory_obj = new Donation($conn);

include_once('../model/donor_model.php');
$donor_object = new Donor($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'add_hospital':

		////////// Get required variables from add hospital form //////////
		$name     = trim($_POST['hosp_name']);
		$location = trim($_POST['location']);
		$contact  = "0" . $_POST['con_no'];
		$email    = trim($_POST['email']);

		////////// Execute add new hospital method //////////
		$result = $hospital_object->add_hospital($name, $location, $contact, $email);

		////////// If method success //////////
		if ($result) {
			$msg = "Successfully Added Hospital $name";
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
			window.location = "../view/mng_hospitals.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'hosp_exist':

		////////// Get required variables from ajax request //////////
		$name = trim($_POST['name']);
		$loc  = trim($_POST['loc']);

		////////// Get hospital details by name and location //////////
		$result = $hospital_object->hosp_exist($name, $loc);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'search_hosp':

		////////// Get hospital ID from ajax request //////////
		$hosp_id = $_POST['hosp_id'];

		////////// Get hospital details by hospital ID //////////
		$result = $hospital_object->get_SpecificHospital($hosp_id);

		////////// If method success //////////
		if ($result) {
			////////// Fetch data //////////
			$row = $result->fetch_assoc();
			$array = array(substr($row['contact_no'], 1), $row['email']);
		} else {
			////////// If method fail //////////
			$array = array("NoData");
		}

		////////// json encode and pass response data //////////
		echo json_encode($array);

		break;

	case 'update_hospital':

		////////// Get required variables from update hospital form //////////
		$hosp_id  = $_POST['hosp_id'];
		$contact  = "0" . $_POST['up_conno'];
		$email    = trim($_POST['up_email']);

		////////// Execute update hospital method //////////
		$result = $hospital_object->update_hospital($hosp_id, $contact, $email);

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

	?>
		<script>
			window.location = "../view/mng_hospitals.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'remove_hospital':

		////////// Get hospital ID from view page //////////
		$hosp_id  = $_REQUEST['hosp_id'];

		////////// Execute remove hospital method //////////
		$result = $hospital_object->remove_hospital($hosp_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Successfully Removed";
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
			window.location = "../view/mng_hospitals.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'add_schedule':

		////////// Get required variables from add new schedule form //////////
		$hosp_id  = $_POST['venue'];
		$date     = isset($_POST['date']) ? $_POST['date'] : "";
		$str_time = $_POST['str_time'];
		$end_time = $_POST['end_time'];
		$slots    = $_POST['slots'];

		$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : "";
		$to_date   = isset($_POST['to_date']) ? $_POST['to_date'] : "";

		////////// If $date not set //////////
		if ($from_date != "" && $to_date != "") {

			////////// Covert date gap to PHP time() method and get it as days //////////
			$date_gap  = (strtotime($to_date) - strtotime($from_date)) / 86400;

			////////// Set schedules while dates gap completed //////////
			for ($i = 0; $i <= $date_gap; $i++) {
				$set_date = strtotime("$i day", strtotime($from_date));
				$set_date = date("Y-m-d", $set_date);
				////////// Execute add schedule method //////////
				$result = $schedule_object->add_schedule($hosp_id, $set_date, $str_time, $end_time, $slots);
			}
		} else {
			////////// Execute add schedule method for single date //////////
			$result = $schedule_object->add_schedule($hosp_id, $date, $str_time, $end_time, $slots);
		}

		////////// If process success //////////
		if ($result) {
			$msg = "Successfully Added New Schedule";
			$res_status = '1';
		} else {
			////////// If process fail //////////
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_schedule.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'schedule_exist':

		////////// Get required variables from ajax requests //////////
		$venue = isset($_POST['venue']) ? $_POST['venue'] : '';
		$date  = isset($_POST['date']) ? $_POST['date'] : '';
		$time  = isset($_POST['time']) ? $_POST['time'] : '';

		////////// Get schedule data by venue, date and time //////////
		$result = $schedule_object->find_ScheduleID($venue, $date, $time);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo "fail";
		} else {
			////////// If result has no records //////////
			echo "ok";
		}

		break;

	case 'get_dates':

		////////// Get location(venue) ID from ajax request //////////
		$location = $_POST['loc_id'];

		////////// Get all scheduled dates by venue ID //////////
		$result = $schedule_object->get_dates($location);
		$output = "<option value=''>---</option>";

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			////////// Fetch data //////////
			while ($row = $result->fetch_assoc()) {

				////////// If has no available slots //////////
				if ($row['av_slots'] < 1) {
					$output .= "<option style='color:#F00;' disabled>" . $row['set_date'] . " >> No Slots Avilable</option>";
				} else {
					////////// If has available slots //////////
					$output .= "<option style='color:#090;' value='" . $row['set_date'] . "'>" . $row['set_date'] . " >> " . $row['av_slots'] . " Slots Avilable</option>";
				}
			}
		}

		////////// Pass the output ////////// 
		echo $output;

		break;

	case 'check_appointValidation':

		////////// Get donor's NIC and selected date from ajax request //////////
		$nic_no = $_POST['nic'];
		$date = $_POST["date"];

		////////// Get appointment details by donor NIC and selected date //////////
		$result = $appoint_object->get_AppointValidation($nic_no, $date);

		////////// Get donor details by NIC //////////
		$get_donor = $donor_object->Donor_searchByNIC($nic_no);
		$donor_row = $get_donor->fetch_assoc();

		////////// Get donation details by donor ID and selected date //////////
		$result_2 = $donHistory_obj->get_AppointValidation($donor_row['donor_id'], $date);

		////////// If either result has records //////////
		if ($result->num_rows > 0) {
			echo "appfail";
		} else if ($result_2->num_rows > 0) {
			echo "donfail";
		} else {
			echo "ok";
		}

		break;

	case 'get_times':

		////////// Get selected date from ajax request //////////
		$date = $_POST['date'];
		$location = $_POST['loc_id'];

		////////// Get all scheduled times by selected date //////////
		$result = $schedule_object->get_times($location, $date);
		$output = "<option value=''>---</option>";

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			////////// Fetch data //////////
			while ($row = $result->fetch_assoc()) {

				////////// If has no available slots //////////
				if ($row['av_slots'] < 1) {
					$output .= "<option style='color:#F00;' disabled>" . $row['start_time'] . " To " . $row['end_time'] . " >> No Slots Avilable</option>";
				} else {
					////////// If has available slots //////////
					$output .= "<option style='color:#090;' value='" . $row['start_time'] . "'>" . $row['start_time'] . " To " . $row['end_time'] . " >> " . $row['av_slots'] . " Slots Avilable</option>";
				}
			}
		}

		////////// Pass the output //////////
		echo $output;

		break;

	case 'update_schedule':

		////////// Get required variables from update schedule form //////////
		$schedule_id = $_REQUEST['sched_id'];
		$old_slots   = $_POST['old_slots'];
		$new_slots   = $_POST['new_slots'];

		////////// Get current appointments count by schedule ID //////////
		$get_appCount = $appoint_object->count_AppointmentByScheduleID($schedule_id);
		$row_getCount = $get_appCount->fetch_assoc();

		////////// If current appointments count less than new slots //////////
		if ($row_getCount['counted'] < $new_slots) {

			////////// Execute update schedule method //////////
			$result = $schedule_object->update_schedule($schedule_id, $old_slots, $new_slots);

			////////// If method success //////////
			if ($result) {
				$msg = "Changes Applied";
				$res_status = '1';
			} else {
				////////// If method fail //////////
				$msg = "oops... something went wrong please try again later";
				$res_status = '0';
			}
		} else {
			////////// If current appointments count exceeds new slots //////////
			$msg = " Sorry...The number of appointments made to this schedule exceeds the number of new slots !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_schedule.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'remove_schedule':

		////////// Get schedule id from view page //////////
		$schedule_id  = $_REQUEST['schedule_id'];

		////////// Execute remove schedule method //////////
		$result = $schedule_object->remove_schedule($schedule_id);

		////////// Get appointment details from schedule ID //////////
		$result_2 = $appoint_object->get_AppointmentByScheduleID($schedule_id);

		////////// If method success //////////
		if ($result) {

			include_once('../controller/mail_controller.php');
			$mail_object = new mail_send();

			//////////// Send Email to Donor ///////////
			$subject = "Appointment Cancellation";

			while ($appInfo = $result_2->fetch_assoc()) {

				$body = "<h3>Mr./Mrs." . $appInfo["donor_name"] . "</h3> <h2>Sorry, we regret to inform you that your appointment was unfortunately canceled due to some reason in our organization !.You can make appintment again !!<h3>";

				////////// If donor has email address //////////
				if ($appInfo['donor_email'] != "NULL" && $appInfo['donor_email'] != "") {
					$mail_object->Send_Mail($appInfo['donor_email'], $subject, $body);
				}
			}

			$msg = "Successfully Removed";
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
			window.location = "../view/mng_schedule.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'set_appointment':

		////////// Get required variables from make appointment form //////////
		$venue = $_POST['venue'];
		$date  = $_POST['pref_date'];
		$time  = $_POST['pref_time'];
		$donor_nic = $_POST['donor_nic'];

		////////// Get hospital details by hospital ID //////////
		$get_hosp = $hospital_object->get_SpecificHospital($venue);
		$hosp_array = $get_hosp->fetch_assoc();

		////////// Get donor details by donor ID //////////
		$don_search = $donor_object->Donor_searchByNIC($donor_nic);
		$don_array = $don_search->fetch_assoc();

		////////// Find schedule ID //////////
		$result = $schedule_object->find_ScheduleID($venue, $date, $time);

		////////// If found schedule //////////
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			////////// Execute make new appointment method //////////
			$result_2 = $appoint_object->set_appointment($row['schedule_id'], $donor_nic);

			////////// If make appointment successfully //////////
			if ($result_2) {
				////////// Decrease available slots count by one //////////
				$result_3 = $schedule_object->decrease_slots($row['schedule_id']);

				$msg = "Appointment is Scheduled !";
				$res_status = '1';

				include_once('../controller/mail_controller.php');
				$mail_object = new mail_send();

				//////////// Send Email to Donor ///////////
				$subject = "Make Appointment";
				$body = "<h3>Your Appointment Was Successfully Scheduled !.<h3><br><b>Venue :- " . $hosp_array['hospital_name'] . " - " . $hosp_array['location'] . "<br> Date :- " . $date . "<br> Time :- " . $time . "";

				////////// If donor has email address //////////
				if ($don_array['donor_email'] != "NULL" && $don_array['donor_email'] != "") {
					$mail_object->Send_Mail($don_array['donor_email'], $subject, $body);
				}

				////////// Send Email to the Hospital as well //////////////	
				$subject = "Make Appointment";
				$body = "<h2>Hospital Has Received a New Appointment</h2><br><b>Date :- ." . $date . "<br> Time :- " . $time . "<br> Donor N.I.C. :- " . $donor_nic . "";
				$mail_object->Send_Mail($hosp_array['email'], $subject, $body);
			} else {
				////////// If process fail //////////
				$msg = "oops... something went wrong please try again later";
				$res_status = '0';
			}
		} else {
			////////// If unable to found schedule //////////
			$msg = "oops... unable to found schedule. Task fail";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/make_appointment.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'cancel_appoint':

		////////// Get required variables from view page //////////		
		$app_id = $_REQUEST['app_id'];
		$schedule_id = $_REQUEST['schedule_id'];
		$donor_nic = $_REQUEST['donor_nic'];

		////////// Execute remove appointment method //////////
		$result = $appoint_object->cancel_Appointment($app_id);
		////////// Get schedule details by schedule ID //////////
		$result_2 = $schedule_object->get_specificShedule($schedule_id);
		$row_2 = $result_2->fetch_assoc();

		////////// Get donor details by NIC //////////
		$don_search = $donor_object->Donor_searchByNIC($donor_nic);
		$don_array = $don_search->fetch_assoc();

		////////// Get hospital details by hospital ID //////////
		$result_3 = $hospital_object->get_SpecificHospital($row_2['hospital_id']);
		$row_3 = $result_3->fetch_assoc();

		////////// If appointment remove successfully //////////
		if ($result) {

			////////// Increase available slots count by one //////////
			$schedule_object->increase_slots($schedule_id);

			///////// Inform that by send email to the Hospital //////////
			$subject = "Appointment Cancellation";
			$body = "<h2>The Appointment With The Following Details Was Cancelled Recently.</h2><br><b>Date :- ." . $row_2['set_date'] . "<br> Time :- " . $row_2['start_time'] . "<br> Donor N.I.C. :- " . $donor_nic . "";

			include_once('../controller/mail_controller.php');
			$mail_object = new mail_send();
			$mail_object->Send_Mail($row_3['email'], $subject, $body);

			$subject = "Appointment Cancellation";
			$body = "Mr./Mrs. " . $don_array['donor_name'] . " You Have Been Cancelled Your Blood Donation Appointment Recently";

			////////// Send email to the donor //////////
			if ($don_array['donor_email'] != NULL && $don_array['donor_email'] != "") {
				$mail_object->Send_Mail($don_array['donor_email'], $subject, $body);
			}

			$msg = "Appointment Cancelled";
			$res_status = '1';
		} else {
			$msg = "oops... something went wrong please try again later";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/mng_appointments.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}
?>