<?php
include_once('../common/db_connection.php');

class Patient
{

	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_patient($name, $nic, $gender, $age, $blood_grp)
	{


		$sql  = "INSERT IGNORE INTO patient(patient_name,nic_no,gender,age,blood_grp) " .
			"VALUES('$name','$nic','$gender',$age,'$blood_grp')";

		$result = $this->conn->query($sql);
		return $result;
	}

	function patient_search($nic)
	{

		$sql  = "SELECT * FROM patient " .
			"WHERE nic_no='$nic'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function update_patient($patient_id, $name, $nic, $gender, $age, $blood_grp)
	{


		$sql  = "UPDATE patient " .
			"SET patient_name='$name',nic_no='$nic',gender='$gender',age=$age,blood_grp='$blood_grp' " .
			"WHERE patient_id=" . $patient_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_Allpatients()
	{

		$sql  = "SELECT * FROM patient p INNER JOIN blood_grptb grp ON p.blood_grp = grp.grp_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificPatient($patient_id)
	{

		$sql  = "SELECT * FROM patient p INNER JOIN blood_grptb grp ON p.blood_grp = grp.grp_id " .
			"WHERE patient_id=" . $patient_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_patient($patient_id)
	{

		$sql  = "DELETE FROM patient " .
			"WHERE patient_id=" . $patient_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	///////////////// Public Accesses ///////////////////
	public function give_Allpatients()
	{
		return $this->get_Allpatients();
	}
}


class Blood_Request
{

	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_request($prior, $name, $age, $gender, $weight, $nic, $bht, $ward, $hosp, $blood_grp,  $mblood_grp, $diagn, $trans_his, $trans_when, $symptom, $hb_lvl, $hb_date, $indic_proced, $indic_date, $req_amount, $user_id)
	{


		$sql  = "INSERT INTO blood_request(priority,name,age,gender,weight,nic_no,bht,ward,hospital,blood_grp,mblood_grp,diagnosis,trans_history,trans_when,react_symptom,hb_level,hb_tested_date,indicate_procedure,indicate_date,require_amount,filler_user_id) " .
			"VALUES($prior,'$name',$age,'$gender',$weight,'$nic',$bht,'$ward','$hosp', $blood_grp, $mblood_grp,'$diagn','$trans_his','$trans_when','$symptom','$hb_lvl','$hb_date','$indic_proced','$indic_date',$req_amount,$user_id)";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	function set_checked($req_id)
	{

		$sql  = "UPDATE blood_request " .
			"SET check_status = 1 " .
			"WHERE request_id = $req_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_compType($req_id, $comp_type)
	{

		$sql  = "UPDATE blood_request " .
			"SET comp_type = $comp_type " .
			"WHERE request_id = $req_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_PendingRequests()
	{

		$sql  = "SELECT * FROM blood_request BR WHERE check_status = 0";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificRequests($request_id)
	{

		$sql  = "SELECT * FROM blood_request " .
			"WHERE request_id =" . $request_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function change_ReqAmount($req_id, $amount)
	{

		$sql  = "UPDATE blood_request " .
			"SET require_amount = $amount " .
			"WHERE request_id =" . $req_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function request_reject($req_id, $rej_reason, $user_id)
	{

		$sql  = "INSERT INTO request_reject(request_id,rej_reason,checker_user_id) " .
			"VALUES($req_id,'$rej_reason',$user_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function request_approval($req_id, $anti_a, $anti_ab, $anti_b, $anti_d, $cell_a, $cell_b, $cell_o, $grp, $s1_37, $s2_37, $s1_iat, $s2_iat, $user_id)
	{

		$sql  = "INSERT INTO request_approval(request_id,anti_a,anti_ab,anti_b,anti_d,cell_a,cell_b,cell_o,grp,s1_37,s2_37,s1_iat,s2_iat,checker_user_id) " .
			"VALUES($req_id,$anti_a,$anti_ab,$anti_b,$anti_d,$cell_a,$cell_b,$cell_o, $grp,'$s1_37','$s2_37','$s1_iat','$s2_iat',$user_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_AllApprovedRequests()
	{

		$sql  = "SELECT * FROM request_approval A, blood_request P " .
			"WHERE A.request_id = P.request_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_issued($req_id)
	{

		$sql  = "UPDATE request_approval " .
			"SET issue_status = 1 " .
			"WHERE request_id =" . $req_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_IssuePendingRequests()
	{

		$sql  = "SELECT * FROM request_approval A, blood_request P " .
			"WHERE A.issue_status = 0 AND A.request_id = P.request_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificApprovedRequest($req_id)
	{

		$sql  = "SELECT * FROM request_approval " .
			"WHERE request_id = " . $req_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_AllRejectedRequests()
	{

		$sql  = "SELECT * FROM request_reject R , blood_request P " .
			"WHERE R.request_id = P.request_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificRejectedRequest($req_id)
	{

		$sql  = "SELECT * FROM request_reject " .
			"WHERE request_id = " . $req_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_request($req_id)
	{

		$sql  = "DELETE FROM blood_request " .
			"WHERE request_id = " . $req_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	////////////////// Public Accesses ////////////////
	public function give_PendingRequests()
	{
		return $this->get_PendingRequests();
	}
}
