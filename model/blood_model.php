<?php
include_once('../common/db_connection.php');

class Blood_Grp
{

	private $conn;
	private $table = "blood_grptb";

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	protected function get_Allgroups()
	{
		$sql  = "SELECT * FROM $this->table";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function get_SpecificGroup($grp_id)
	{
		$sql  = "SELECT * FROM $this->table WHERE grp_id = " . $grp_id;

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function modify_grp($grp_id, $descript)
	{
		$sql  = "UPDATE $this->table SET description = '$descript' " .
			"WHERE grp_id = $grp_id";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	////////////////////////////// Public Access ///////////////////////////////
	public function give_Allgroups()
	{
		return $this->get_Allgroups();
	}

	public function give_SpecificGroup($grp_id)
	{
		return $this->get_SpecificGroup($grp_id);
	}

	public function update_grp($grp_id, $descript)
	{
		return $this->modify_grp($grp_id, $descript);
	}
}


class Blood_Component
{

	private $conn;
	private $table = "blood_comptb";

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	protected function get_Allcomponents()
	{
		$sql  = "SELECT * FROM $this->table";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function get_SpecificComp($comp_id)
	{
		$sql  = "SELECT * FROM $this->table " .
			"WHERE comp_id = " . $comp_id;

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function modify_comp($comp_id, $descript)
	{
		$sql  = "UPDATE $this->table SET description = '$descript' " .
			"WHERE comp_id = $comp_id";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function remove_comp($comp_id)
	{
		$sql  = "DELETE FROM $this->table " .
			"WHERE comp_id=" . $comp_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	////////////////////////////// Public Access ///////////////////////////////
	public function give_Allcomponents()
	{
		return $this->get_Allcomponents();
	}

	public function give_SpecificComp($comp_id)
	{
		return $this->get_SpecificComp($comp_id);
	}

	public function update_comp($comp_id, $descript)
	{
		return $this->modify_comp($comp_id, $descript);
	}

	public function delete_comp($comp_id)
	{
		return $this->remove_comp($comp_id);
	}
}


class Raw_Bloods
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function get_Donations()
	{
		$sql  = "SELECT YEAR(donated_date) AS don_year, MONTH(donated_date) AS don_month, COUNT(bag_id) AS don_count " .
			"FROM donation_history " .
			"GROUP BY YEAR(donated_date), MONTH(donated_date)";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	function get_Accepted()
	{
		$sql  = "SELECT YEAR(checked_date) AS chk_year, MONTH(checked_date) AS chk_month, COUNT(bag_id) AS chk_count " .
			"FROM tested_donation " .
			"WHERE check_status = 1 " .
			"GROUP BY YEAR(checked_date), MONTH(checked_date)";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	function get_Rejected()
	{
		$sql  = "SELECT YEAR(checked_date) AS chk_year, MONTH(checked_date) AS chk_month, COUNT(bag_id) AS chk_count " .
			"FROM tested_donation " .
			"WHERE check_status = 0 " .
			"GROUP BY YEAR(checked_date), MONTH(checked_date)";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	function get_CurrentBloods()
	{
		$sql  = "SELECT grp.grp_id, grp.grp_name, D.blood_grp, COUNT(donation.bag_id) AS don_count " .
			"FROM donor D INNER JOIN donation ON D.donor_id = donation.donor_id " .
			"INNER JOIN blood_grptb grp ON D.blood_grp = grp.grp_id " .
			"GROUP BY D.blood_grp";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}
}
