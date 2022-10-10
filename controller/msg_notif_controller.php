<?php
ini_set('session.cookie_lifetime', '86400');
session_start();

///////////////////////// Include msg_notif, user and role models /////////////////////////
include('../model/msg_notif_model.php');
$msg_object = new Message($conn);
$notif_object = new Notification($conn);

include_once('../model/user_model.php');
$user_object = new User($conn);

include_once('../model/role_model.php');
$role_object = new Role($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'send_msg':

		////////// Get required variables from send message form //////////
		$msg_body  = $_POST['msg_body'];
		$sender    = $_SESSION['user_id'];
		$receiver  = $_POST['receiver'];

		////////// Execute insert new message method //////////
		$result = $msg_object->create_msg($msg_body, $sender, $receiver);

		////////// If method success //////////
		if ($result) {
			$msg = "Message Sent !";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops...somthing went wrong..Please try again later !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

?>
		<script>
			window.location = "../view/messages.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'get_allMsgs':

		////////// Get user ID from session //////////
		$user_id = $_SESSION['user_id'];

		////////// Get all messages by user ID //////////
		$result = $msg_object->give_Messages($user_id);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			////////// Create empty variable for output //////////
			$output = "";
			$sender = "";

			////////// Fetch data untill complete records //////////
			while ($row = $result->fetch_assoc()) {

				////////// Execute insert new message method //////////
				$user_result = $user_object->give_info($row['sender_id']);
				$user_row = $user_result->fetch_assoc();

				////////// Execute insert new message method //////////
				$role_result = $role_object->get_specificRole($user_row['user_role']);
				$role_row = $role_result->fetch_assoc();

				if ($sender != $row['sender_id']) {

					$seen_count = 0;

					$sender = $row['sender_id'];
					$output .= "<h6><kbd>From : " . $user_row['fname'] . " " . $user_row['lname'] . " (" . $role_row['role_name'] . ")</kbd> &nbsp;";

					$output .= "<button class='btn-info' value='" . $sender . "'><u>Read</u> <i class='fa fa-arrow-circle-down'></i></button>";

					$output .= "</h6><br>" .
						"<div class='msgBody " . $sender . "' style='margin-left:10%; overflow-y:scroll; max-height:200px; position:relative;'>";

					$result_2 = $msg_object->give_SpecificMsgsBySender($user_id, $row['sender_id']);

					while ($row_2 = $result_2->fetch_assoc()) {

						$isBroadcast = ($row_2['receiver_id'] == -1) ? "&nbsp;&nbsp;<kbd style='background-color:#00C;'>BROADCASTED</kbd>" : "";

						if ($row['seen_status'] == 0) {
							$seen_count++;
							$output .= "<p style='color:#000; background-color:#D7D7D7; padding-left:5px;'><kbd class='new_badge'>New</kbd>&nbsp;" . $row_2['msg_body'] . $isBroadcast . "";

							$output .= "&nbsp;&nbsp; <code style='color:#F00;'>At " . $row_2['sent_time'] . "</code>";
							$output .= "<a class='btn btn-dark btn-sm' href='../controller/msg_notif_controller.php?status=remove_oneMsg&msg_id=" . $row_2['msg_id'] . "' style='float:right; border-radius:50%; margin-right:10px;' onClick='return dell_Single(this);'><i class='fa fa-times'></i></a></p>";
						} else {
							$output .= "<p style='color:#000; background-color:#D7D7D7; padding-left:5px;'>" . $row_2['msg_body'] . $isBroadcast . "";

							$output .= "&nbsp;&nbsp; <code style='color:#F00;'>At " . $row_2['sent_time'] . "</code>";
							$output .= "<a class='btn btn-dark btn-sm' href='../controller/msg_notif_controller.php?status=remove_oneMsg&msg_id=" . $row_2['msg_id'] . "'  style='float:right; border-radius:50%; margin-right:10px;' onClick='return dell_Single(this);'><i class='fa fa-times'></i></a></p>";
						}
					}

					$output .= "<button class='btn btn-warning reply' value='" . $sender . "'>Reply <i class='fa fa-share'></i></button>&nbsp;&nbsp;<a href='../controller/msg_notif_controller.php?status=remove_bulkMsg&receiver=" . $sender . "' class='btn btn-dark' onClick='return dell_All(this);'>&nbsp;<i class='fa fa-trash-alt'></i>&nbsp;</a></div><hr>";
				}
			}
		} else {
			$output = "<h2 align='center'>********* No Messages **********</h2>";
		}

		echo $output;

		break;

	case 'msg_refresh':

		////////// Execute insert new message method //////////
		$result = $msg_object->give_UnseenMsgs($_SESSION['user_id']);

		////////// If result has records //////////
		if ($result->num_rows > 0) {
			echo $result->num_rows;
		} else {
			////////// If result has no records //////////
			echo "noMsg";
		}

		break;

	case 'set_MsgSeen':

		////////// Get sender ID from ajax //////////
		$sender = trim($_POST['sender_id']);

		////////// Execute insert new message method //////////
		$result = $msg_object->update_seen($sender);

		break;

	case 'remove_bulkMsg':

		////////// Get receiver id from view page //////////
		$receiver = $_REQUEST['receiver'];
		////////// Get user id from session //////////
		$user_id = $_SESSION['user_id'];

		////////// Execute insert new message method //////////
		$result = $msg_object->delete_BulkMsg($user_id, $receiver);

		////////// If method success //////////
		if ($result) {
			$msg = "Messages Removed";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops...somthing went wrong..Please try again later !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/messages.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'remove_oneMsg':

		////////// Get message ID from view page //////////
		$msg_id = $_REQUEST['msg_id'];

		////////// Execute insert new message method //////////
		$result = $msg_object->delete_SingleMsg($msg_id);

		////////// If method success //////////
		if ($result) {
			$msg = "Message Removed";
			$res_status = '1';
		} else {
			////////// If method fail //////////
			$msg = "oops...somthing went wrong..Please try again later !";
			$res_status = '0';
		}

		////////// Encode Response Messages and redirect to the view page //////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

	?>
		<script>
			window.location = "../view/messages.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;

	case 'notif_refresh':

		////////// Execute insert new message method //////////
		$result = $notif_object->give_unseenNotif();

		////////// If method success //////////
		if ($result) {
			$notif_count = $result->num_rows;
			echo $notif_count;
		} else {
			echo "noNotif";
		}

		break;

	case 'set_seenNotif':

		////////// Update notification seen status //////////
		$result = $notif_object->update_seen();

		break;
}
?>