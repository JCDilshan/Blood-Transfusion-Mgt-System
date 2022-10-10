<?php
include_once('../common/db_connection.php');
$db_object = new DB();

class Doctor_camp
{

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_doc($doc_id, $camp_id)
	{
		$sql  = "INSERT INTO doctor_camp(doc_id,camp_id) " .
			"VALUES($doc_id,$camp_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_docByDocID($doc_id)
	{
		$sql  = "SELECT camp_id FROM doctor_camp " .
			"WHERE doc_id = $doc_id AND held_status = 1";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_doc($camp_id)
	{
		$sql  = "SELECT * FROM doctor_camp DC INNER JOIN doctor D ON D.doc_id = DC.doc_id " .
			"WHERE DC.camp_id = $camp_id " .
			"GROUP BY D.doc_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_held($camp_id)
	{
		$sql  = "UPDATE doctor_camp " .
			"SET held_status = 1 " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_docs($camp_id)
	{
		$sql  = "DELETE FROM doctor_camp " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}
}

class Nurse_camp
{
	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_nurse($nurse_id, $camp_id)
	{
		$sql  = "INSERT INTO nurse_camp(nurse_id,camp_id) " .
			"VALUES($nurse_id,$camp_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_nurseByNurseID($nurse_id)
	{
		$sql  = "SELECT camp_id FROM nurse_camp " .
			"WHERE nurse_id = $nurse_id AND held_status = 1";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_nurse($camp_id)
	{
		$sql  = "SELECT * FROM nurse_camp NC INNER JOIN nurse N ON N.nurse_id = NC.nurse_id " .
			"WHERE NC.camp_id = $camp_id " .
			"GROUP BY N.nurse_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_held($camp_id)
	{
		$sql  = "UPDATE nurse_camp " .
			"SET held_status = 1 " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_nurse($camp_id)
	{
		$sql  = "DELETE FROM nurse_camp " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}
}

class Member_camp
{
	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_mem($mem_id, $camp_id)
	{
		$sql  = "INSERT INTO ostaff_camp(mem_id,camp_id) " .
			"VALUES($mem_id,$camp_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_memberByMemID($mem_id)
	{
		$sql  = "SELECT camp_id FROM ostaff_camp " .
			"WHERE mem_id = $mem_id AND held_status = 1";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_mem($camp_id)
	{
		$sql  = "SELECT * FROM ostaff_camp OC INNER JOIN other_staff O ON O.mem_id = OC.mem_id " .
			"WHERE OC.camp_id = $camp_id " .
			"GROUP BY O.mem_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_held($camp_id)
	{
		$sql  = "UPDATE ostaff_camp " .
			"SET held_status = 1 " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_mem($camp_id)
	{
		$sql  = "DELETE FROM ostaff_camp " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}
}
