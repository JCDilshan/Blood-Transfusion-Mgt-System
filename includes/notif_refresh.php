<div>&nbsp;</div>
<?php

include_once('../model/msg_notif_model.php');
$notif_object = new Notification($conn);

///////////////////////// Get notifications /////////////////////////////
$notif_result = $notif_object->give_notif();

///////////////////////// If has notifications /////////////////////////////
if ($notif_result->num_rows > 0) {
	///////////////////////// Fetch data /////////////////////////////
	while ($notif_row = $notif_result->fetch_assoc()) {
		if ($notif_row['seen_status'] == 0) {
?>
			<p>>> <kbd>New</kbd><?php echo $notif_row['notif_body'] . "&nbsp;&nbsp;&nbsp;&nbsp; <span style='float:right;' class='text-primary'><i>" . date("jS M Y g:i A", strtotime($notif_row['date_time'])) . "</i></span><hr>"; ?></p>
		<?php } else { ?>
			<p>>> <?php echo $notif_row['notif_body'] . "&nbsp;&nbsp;&nbsp;&nbsp; <span style='float:right;' class='text-primary'><i>" . date("jS M Y g:i A", strtotime($notif_row['date_time'])) . "</i></span><hr>"; ?></p>
<?php }
	}
} else {
	///////////////////////// If no notifications /////////////////////////////
	echo "<h3 align='center'><b>No Notifications</b></h3>";
} ?>