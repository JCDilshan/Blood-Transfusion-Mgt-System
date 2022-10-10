<?php

include_once('../model/blood_model.php');

$bloodGrp_object = new Blood_Grp($conn);
$bloodComp_object = new Blood_Component($conn);

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

///////////////////////// Switch request status /////////////////////////
switch ($status) {

	case 'updateGrp':

		///////////////////////// Get form variable values /////////////////////////
		$grp_id     = $_REQUEST['grp_id'];
		$descript   = $_POST['description'];

		///////////////////////// Execute blood group update method /////////////////////////
		$result = $bloodGrp_object->update_grp($grp_id, $descript);

		///////////////////////// If process is successed /////////////////////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			///////////////////////// If process fail /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response messages /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

		///////////////////////// Redirect to the view page /////////////////////////
?>
		<script>
			window.location = "../view/blood_grp.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
	<?php

		break;

	case 'updateComp':

		///////////////////////// Get form variable values /////////////////////////
		$comp_id     = $_REQUEST['comp_id'];
		$descript   = $_POST['description'];

		///////////////////////// Execute blood Component update method /////////////////////////
		$result = $bloodComp_object->update_comp($comp_id, $descript);

		///////////////////////// If process is successed /////////////////////////
		if ($result) {
			$msg = "Changes Applied";
			$res_status = '1';
		} else {
			///////////////////////// If process is failed /////////////////////////
			$msg = "Somthing went wrong..Please try again later<br> Task Fail !";
			$res_status = '0';
		}

		///////////////////////// Encode response messages /////////////////////////
		$msg = base64_encode($msg);
		$res_status = base64_encode($res_status);

		///////////////////////// Redirect to the view page /////////////////////////
	?>
		<script>
			window.location = "../view/blood_components.php?msg=<?php echo $msg; ?>&resstatus=<?php echo $res_status; ?>";
		</script>
<?php

		break;
}

?>