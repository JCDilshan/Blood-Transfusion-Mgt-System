<?php
include_once('../common/db_connection.php');

class Doctor
{

	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_doctor($fname, $lname, $nic, $con_no, $gender, $email, $qualif)
	{

		$sql  = "INSERT INTO doctor(fname,lname,nic_no,contact_no,gender,email,qualif,reg_date) " .
			"VALUES('$fname','$lname','$nic','$con_no','$gender','$email','$qualif',CURDATE())";

		$result = $this->conn->query($sql);
		return $result;
	}

	function doctor_searchByNIC($nic)
	{

		$sql  = "SELECT * FROM doctor " .
			"WHERE nic_no='$nic'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function update_doctor($doc_id, $fname, $lname, $nic, $con_no, $gender, $email, $qualif)
	{

		$sql  = "UPDATE doctor " .
			"SET fname='$fname',lname='$lname',nic_no='$nic',contact_no='$con_no',gender='$gender',email='$email',qualif='$qualif' " .
			"WHERE doc_id=" . $doc_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function active_blockDoc($doc_id, $switch_status)
	{

		$sql  = "UPDATE doctor " .
			"SET doc_status = $switch_status " .
			"WHERE doc_id =" . $doc_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_Alldoctors()
	{

		$sql  = "SELECT * FROM doctor";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_Specificdoctor($doc_id)
	{

		$sql  = "SELECT * FROM doctor " .
			"WHERE doc_id=" . $doc_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function unreserved_doctors($select_date)
	{

		$sql  = "SELECT * FROM doctor D " .
			"NATURAL LEFT JOIN doctor_camp DC " .
			"WHERE D.doc_status = 1 AND DC.doc_id IS NULL";

		$sql_2 = "SELECT * FROM doctor D INNER JOIN doctor_camp DC ON D.doc_id = DC.doc_id INNER JOIN camp C ON C.camp_id = DC.camp_id " .
			"WHERE C.date <> '$select_date' AND " .
			"DC.doc_id NOT IN (SELECT DC.doc_id FROM camp C INNER JOIN doctor_camp DC ON C.camp_id = DC.camp_id WHERE C.date = '$select_date') AND " .
			"D.doc_status = 1 GROUP BY D.doc_id";

		$result = $this->conn->query($sql);
		$result_2 = $this->conn->query($sql_2);

		return array($result, $result_2);
	}

	function remove_doctor($doc_id)
	{

		$sql  = "DELETE FROM doctor " .
			"WHERE doc_id=" . $doc_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	//////////////////// Public Accesses ///////////////////
	public function give_Alldoctors()
	{
		return $this->get_Alldoctors();
	}
}


class Nurse
{

	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_nurse($fname, $lname, $nic, $con_no, $gender, $email, $qualif)
	{

		$sql  = "INSERT INTO nurse(fname,lname,nic_no,contact_no,gender,email,qualif,reg_date) " .
			"VALUES('$fname','$lname','$nic','$con_no','$gender','$email','$qualif',CURDATE())";

		$result = $this->conn->query($sql);
		return $result;
	}

	function nurse_searchByNIC($nic)
	{

		$sql  = "SELECT * FROM nurse " .
			"WHERE nic_no='$nic'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function update_nurse($nurse_id, $fname, $lname, $nic, $con_no, $gender, $email, $qualif)
	{

		$sql  = "UPDATE nurse " .
			"SET fname='$fname',lname='$lname',nic_no='$nic',contact_no='$con_no',gender='$gender',email='$email',qualif='$qualif' " .
			"WHERE nurse_id=" . $nurse_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function active_blockNurse($nurse_id, $switch_status)
	{

		$sql  = "UPDATE nurse " .
			"SET nurse_status = $switch_status " .
			"WHERE nurse_id =" . $nurse_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_AllNurse()
	{

		$sql  = "SELECT * FROM nurse";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificNurse($nurse_id)
	{

		$sql  = "SELECT * FROM nurse " .
			"WHERE nurse_id=" . $nurse_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function unreserved_nurse($select_date)
	{

		$sql  = "SELECT * FROM nurse N " .
			"NATURAL LEFT JOIN nurse_camp NC " .
			"WHERE N.nurse_status = 1 AND NC.nurse_id IS NULL";


		$sql_2 = "SELECT * FROM nurse N INNER JOIN nurse_camp NC ON N.nurse_id = NC.nurse_id INNER JOIN camp C ON C.camp_id = NC.camp_id " .
			"WHERE C.date <> '$select_date' AND " .
			"NC.nurse_id NOT IN (SELECT NC.nurse_id FROM camp C INNER JOIN nurse_camp NC ON C.camp_id = NC.camp_id WHERE C.date = '$select_date') AND " .
			"N.nurse_status = 1 GROUP BY N.nurse_id";

		$result = $this->conn->query($sql);
		$result_2 = $this->conn->query($sql_2);

		return array($result, $result_2);
	}

	function remove_nurse($nurse_id)
	{

		$sql  = "DELETE FROM nurse " .
			"WHERE nurse_id=" . $nurse_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	//////////////////// Public Accesses ///////////////////
	public function give_AllNurse()
	{
		return $this->get_AllNurse();
	}
}


class Other_Staff
{

	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_member($fname, $lname, $nic, $con_no, $gender, $email, $qualif)
	{

		$sql  = "INSERT INTO other_staff(fname,lname,nic_no,contact_no,gender,email,qualif,reg_date) " .
			"VALUES('$fname','$lname','$nic','$con_no','$gender','$email','$qualif',CURDATE())";

		$result = $this->conn->query($sql);
		return $result;
	}

	function member_searchByNIC($nic)
	{

		$sql  = "SELECT * FROM other_staff " .
			"WHERE nic_no='$nic'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function update_member($mem_id, $fname, $lname, $nic, $con_no, $gender, $email, $qualif)
	{

		$sql  = "UPDATE other_staff " .
			"SET fname='$fname',lname='$lname',nic_no='$nic',contact_no='$con_no',gender='$gender',email='$email',qualif='$qualif' " .
			"WHERE mem_id=" . $mem_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function active_blockMember($mem_id, $switch_status)
	{

		$sql  = "UPDATE other_staff " .
			"SET mem_status = $switch_status " .
			"WHERE mem_id =" . $mem_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_AllMembers()
	{

		$sql  = "SELECT * FROM other_staff";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificMember($mem_id)
	{

		$sql  = "SELECT * FROM other_staff " .
			"WHERE mem_id=" . $mem_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function unreserved_members($select_date)
	{

		$sql  = "SELECT * FROM other_staff M " .
			"NATURAL LEFT JOIN ostaff_camp MC " .
			"WHERE M.mem_status = 1 AND MC.mem_id IS NULL";

		$sql_2 = "SELECT * FROM other_staff M INNER JOIN ostaff_camp MC ON M.mem_id = MC.mem_id INNER JOIN camp C ON C.camp_id = MC.camp_id " .
			"WHERE C.date <> '$select_date' AND " .
			"MC.mem_id NOT IN (SELECT MC.mem_id FROM camp C INNER JOIN ostaff_camp MC ON C.camp_id = MC.camp_id WHERE C.date = '$select_date') AND " .
			"M.mem_status = 1 GROUP BY M.mem_id";

		$result = $this->conn->query($sql);
		$result_2 = $this->conn->query($sql_2);

		return array($result, $result_2);
	}

	function remove_member($mem_id)
	{

		$sql  = "DELETE FROM other_staff " .
			"WHERE mem_id=" . $mem_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	//////////////////// Public Accesses ///////////////////
	public function give_AllMembers()
	{
		return $this->get_AllMembers();
	}
}
