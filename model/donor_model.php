<?php
include_once('../common/db_connection.php');

class Donor
{

	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_donor($name, $nic, $gender, $dob, $con_no, $disc_id, $email, $blood_grp, $otherInfo)
	{

		$sql  = "INSERT INTO donor(donor_name,donor_nic,donor_gender,donor_dob,donor_contact,district_id,donor_email,blood_grp,donor_otherInfo,reg_date)" .
			"VALUES('$name','$nic','$gender','$dob','$con_no',$disc_id,'$email','$blood_grp','$otherInfo',CURDATE())";

		$result = $this->conn->query($sql);
		return $result;
	}

	function update_donor($donor_id, $name, $nic, $gender, $dob, $con_no, $disc_id, $email, $blood_grp, $otherInfo)
	{

		$sql  = "UPDATE donor " .
			"SET donor_name='$name',donor_nic='$nic',donor_gender='$gender',donor_dob='$dob',donor_contact='$con_no',district_id=$disc_id,donor_email='$email',blood_grp='$blood_grp',donor_otherInfo='$otherInfo' " .
			"WHERE donor_id=" . $donor_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_Alldonors()
	{
		$sql  = "SELECT * FROM donor d INNER JOIN blood_grptb grp ON d.blood_grp = grp.grp_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificDonor($donor_id)
	{
		$sql  = "SELECT * FROM donor d INNER JOIN blood_grptb grp ON d.blood_grp = grp.grp_id " .
			"WHERE d.donor_id=" . $donor_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_DonorByDistrictID($disc_id)
	{
		$sql  = "SELECT * FROM donor " .
			"WHERE district_id=" . $disc_id . " AND donor_email IS NOT NULL";

		$result = $this->conn->query($sql);
		return $result;
	}

	function Donor_searchByNIC($nic)
	{
		$sql  = "SELECT * FROM donor d INNER JOIN blood_grptb grp ON d.blood_grp = grp.grp_id " .
			"WHERE donor_nic='$nic'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function Donor_searchByID($donor_id)
	{
		$sql  = "SELECT * FROM donor d INNER JOIN blood_grptb grp ON d.blood_grp = grp.grp_id " .
			"WHERE donor_id = $donor_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_TopDonors()
	{
		$sql  = "SELECT * FROM donor ORDER BY donated_count DESC LIMIT 6";

		$result = $this->conn->query($sql);
		return $result;
	}

	function active_block($donor_id, $switch_status)
	{
		$sql  = "UPDATE donor " .
			"SET donor_status = $switch_status " .
			"WHERE donor_id =" . $donor_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function donation_increase($donor_id)
	{
		$sql  = "UPDATE donor " .
			"SET donated_count = donated_count + 1 " .
			"WHERE donor_id =" . $donor_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function donation_decrease($donor_id)
	{
		$sql  = "UPDATE donor " .
			"SET donated_count = donated_count - 1 " .
			"WHERE donor_id =" . $donor_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_donor($donor_id)
	{
		$sql  = "DELETE FROM donor " .
			"WHERE donor_id=" . $donor_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	////////////////// Public Accesses ////////////////
	public function give_AllDonors()
	{
		return $this->get_Alldonors();
	}
	public function give_TopDonors()
	{
		return $this->get_TopDonors();
	}
}
