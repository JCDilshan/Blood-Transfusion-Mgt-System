<?php
include_once('../common/db_connection.php');
$db_object = new DB();

class Inventory
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_blood($bag_id, $blood_grp, $comp_type, $sealed_date, $donor_id, $camp_id, $hosp_id)
	{

		$sql  = "INSERT INTO inventory(bag_id,blood_grp,comp_type,expire_date,donor_id,camp_id,hospital_id) " .
			"VALUES('$bag_id', $blood_grp, $comp_type ,DATE_ADD('$sealed_date', INTERVAL 45 DAY),$donor_id,$camp_id,$hosp_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_AllDetails()
	{

		$sql  = "SELECT * FROM inventory inv INNER JOIN blood_grptb grp ON inv.blood_grp = grp.grp_id " .
			"INNER JOIN blood_comptb comp ON inv.comp_type = comp.comp_id " .
			"WHERE inv.issue_status = 1 AND inv.expire_date > CURRENT_DATE";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_bagDetails($bag_id)
	{

		$sql  = "SELECT * FROM inventory inv INNER JOIN blood_grptb grp ON inv.blood_grp = grp.grp_id " .
			"INNER JOIN blood_comptb comp ON inv.comp_type = comp.comp_id " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_RemainGrpCount()
	{

		$sql  = "SELECT grp.grp_name, inv.blood_grp, COUNT(bag_id) AS bag_count FROM inventory inv INNER JOIN blood_grptb grp ON inv.blood_grp = grp.grp_id " .
			"WHERE inv.issue_status = 1 AND inv.expire_date > CURRENT_DATE GROUP BY inv.blood_grp";

		$result = $this->conn->query($sql);
		return $result;
	}

	function active_block($bag_id, $switch_status)
	{

		$sql  = "UPDATE inventory " .
			"SET block_status = $switch_status " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_issue($bag_id)
	{

		$sql  = "UPDATE inventory " .
			"SET issue_status = 0 " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function quick_block($category, $target)
	{

		$sql  = "UPDATE inventory " .
			"SET block_status = 0 " .
			"WHERE " . $category . " = $target OR " . $category . " = '$target'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_bag($bag_id)
	{

		$sql  = "DELETE FROM inventory " .
			"WHERE bag_id = '$bag_id'";

		$result = $this->conn->query($sql);
		return $result;
	}

	function crossMatch_check($blood_grp)
	{

		$sql  = "SELECT * FROM cross_match " .
			"WHERE grp_id = $blood_grp";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_MatchingBags($blood_grp, $comp_type)
	{

		$sql  = "SELECT * FROM inventory inv INNER JOIN blood_grptb grp ON inv.blood_grp = grp.grp_id " .
			"WHERE inv.blood_grp IN($blood_grp) AND inv.comp_type = $comp_type AND (inv.block_status = 1 AND inv.issue_status = 1) ";
		"ORDER BY inv.expire_date";

		$result = $this->conn->query($sql);
		return $result;
	}

	function add_issueHistory($bag_id, $pat_id, $hosp)
	{

		$sql  = "INSERT INTO issue_history(bag_id,patient_id,hospital) " .
			"VALUES('$bag_id',$pat_id,'$hosp')";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_AllIssuedHistory()
	{

		$sql  = "SELECT * FROM issue_history";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_ExpiredHistory()
	{

		$sql  = "SELECT * FROM expired_bags ex INNER JOIN inventory inv ON ex.bag_id = inv.bag_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_issueHistoryByPatID($pat_id)
	{

		$sql  = "SELECT issued_date, hospital, COUNT(bag_id) AS issue_count FROM issue_history " .
			"WHERE patient_id = $pat_id " .
			"GROUP BY issued_date";

		$result = $this->conn->query($sql);
		return $result;
	}


	function get_AddedBlood()
	{

		$sql  = "SELECT YEAR(added_date) AS add_year, MONTH(added_date) AS add_month, COUNT(added_date) AS add_count " .
			"FROM inventory " .
			"GROUP BY YEAR(added_date), MONTH(added_date)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_IssuedBlood()
	{

		$sql  = "SELECT YEAR(issued_date) AS issue_year, MONTH(issued_date) AS issue_month, COUNT(issued_date) AS issue_count " .
			"FROM issue_history " .
			"GROUP BY YEAR(issued_date), MONTH(issued_date)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_ExpiredBlood()
	{

		$sql  = "SELECT YEAR(expired_date) AS exp_year, MONTH(expired_date) AS exp_month, COUNT(expired_date) AS exp_count " .
			"FROM expired_bags " .
			"WHERE expired_date <= CURRENT_DATE " .
			"GROUP BY YEAR(expired_date), MONTH(expired_date)";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_CurrentInven()
	{

		$sql  = "SELECT grp.grp_id, grp.grp_name, inv.blood_grp, COUNT(inv.bag_id) as blcount FROM inventory inv INNER JOIN blood_grptb grp ON inv.blood_grp = grp.grp_id " .
			"WHERE (inv.issue_status = 1 AND inv.block_status = 1) AND inv.expire_date > CURRENT_DATE " .
			"GROUP BY inv.blood_grp";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}
}
