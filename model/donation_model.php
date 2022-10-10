<?php
include_once('../common/db_connection.php');
$db_object = new DB();

class Donation
{
	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function add_donation($bag_id, $donor_id, $camp_id, $hosp_id, $don_date)
	{

		$sql  = "INSERT INTO donation(bag_id,donor_id,camp_id,hospital_id,donated_date) " .        "VALUES('$bag_id',$donor_id,$camp_id,$hosp_id,'$don_date')";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function checkBag_exist($bag_id)
	{

		$sql  = "SELECT * FROM donation " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function All_donations()
	{

		$sql  = "SELECT * FROM donation dn INNER JOIN donor d ON dn.donor_id = d.donor_id " .
			"INNER JOIN blood_grptb grp ON d.blood_grp = grp.grp_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_SpecificDonation($donation_id)
	{

		$sql  = "SELECT * FROM donation " .
			"WHERE donation_id =" . $donation_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function take_proceed($donation_id)
	{

		$sql  = "UPDATE donation SET proc_status = 1 " .
			"WHERE donation_id = " . $donation_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function rollback($donation_id)
	{

		$sql  = "UPDATE donation SET proc_status = 0 " .
			"WHERE donation_id = " . $donation_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function remove_donation($donation_id)
	{

		$sql  = "DELETE FROM donation " .
			"WHERE donation_id = " . $donation_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	/////////////////////////////// Proceeding Donations ////////////////////////////////////
	public function get_ProcDonations()
	{

		$sql  = "SELECT * FROM donation dn INNER JOIN donor d ON dn.donor_id = d.donor_id WHERE proc_status = 1";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_SpecificProcDonations($proc_id)
	{

		$sql  = "SELECT * FROM proc_donation " .
			"WHERE proc_id=" . $proc_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	//////////////////////////////////////// Tested Donations ////////////////////////////////////

	public function add_TestedRecord($bag_id, $donor_id, $camp_id, $hosp_id, $check_status, $checked_date, $rej_reason)
	{

		$sql  = "INSERT INTO tested_donation(bag_id,donor_id,camp_id,hospital_id,check_status,checked_date,reject_reason) " .
			"VALUES('$bag_id',$donor_id,$camp_id,$hosp_id,$check_status,'$checked_date','$rej_reason')";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_TestedDonationHistory()
	{

		$sql  = "SELECT * FROM tested_donation";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function remove_TestedRecord($record_id)
	{

		$sql  = "DELETE FROM tested_donation " .
			"WHERE record_id =" . $record_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function Inventory_pass($bag_id, $donor_id, $camp_id, $hosp_id, $blood_grp, $comp_type, $sealed)
	{

		$sql  = "INSERT INTO inventory_pass(bag_id,donor_id,camp_id,hospital_id,blood_grp,comp_type,sealed_date) " .
			"VALUES('$bag_id',$donor_id,$camp_id,$hosp_id,$blood_grp,$comp_type,'$sealed')";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_InventoryPass()
	{

		$sql  = "SELECT * FROM inventory_pass inp INNER JOIN blood_grptb grp ON inp.blood_grp = grp.grp_id " .
			"INNER JOIN blood_comptb comp ON inp.comp_type = comp.comp_id " .
			"INNER JOIN donor d ON inp.donor_id = d.donor_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_SpecificPassedRecord($bag_id)
	{

		$sql  = "SELECT * FROM inventory_pass inp INNER JOIN blood_grptb grp ON inp.blood_grp = grp.grp_id " .
			"INNER JOIN blood_comptb comp ON inp.comp_type = comp.comp_id " .
			"INNER JOIN donor d ON inp.donor_id = d.donor_id " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function remove_PassRecord($bag_id)
	{

		$sql  = "DELETE FROM inventory_pass " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	///////////////////////////////////// Donation History ////////////////////////////////////

	public function add_Historyrecord($bag_id, $donor_id, $camp_id, $hosp_id, $donated_date)
	{

		$sql  = "INSERT INTO donation_history(bag_id,donor_id,camp_id,hospital_id,donated_date) " .
			"VALUES('$bag_id',$donor_id,$camp_id,$hosp_id,'$donated_date')";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_History()
	{

		$sql  = "SELECT * FROM donation_history";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_HistoryByDonorID($donor_id)
	{

		$sql  = "SELECT donated_date,camp_id,hospital_id,COUNT(bag_id) AS donated_count FROM donation_history " .
			"WHERE donor_id = $donor_id GROUP BY donated_date";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_specificRecord($donor_id, $don_date)
	{

		$sql  = "SELECT * FROM donation_history " .
			"WHERE donor_id = $donor_id AND donated_date = '$don_date'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function set_NewDonCount($donor_id, $don_date, $count)
	{

		$sql  = "UPDATE donation_history " .
			"SET donated_count = donated_count - $count " .
			"WHERE donor_id = $donor_id AND donated_date = '$don_date'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function remove_record($bag_id)
	{

		$sql  = "DELETE FROM donation_history " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_AppointValidation($donor_id, $date)
	{

		$sql  = "SELECT * FROM donation_history DH " .
			"WHERE DH.donor_id = $donor_id AND (DATEDIFF('$date', DH.donated_date) < 90)";

		$result = $this->conn->query($sql);
		return $result;
	}

	////////////////// Public Accesses ////////////////////
	public function give_History()
	{
		return $this->get_History();
	}
}
