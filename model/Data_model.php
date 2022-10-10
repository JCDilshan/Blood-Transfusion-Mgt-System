<?php
include_once('../common/db_connection.php');
$db_object = new DB();

// class Raw_Bloods{

// function get_Donations(){ 
// 	$sql  = "SELECT YEAR(donated_date) AS don_year, MONTH(donated_date) AS don_month, COUNT(bag_id) AS don_count ".
//             "FROM donation_history ".
// 		    "GROUP BY YEAR(donated_date), MONTH(donated_date)";

// 	$result = $this->conn->query($sql);
// 	return $result;		
// 	}

// function get_Accepted(){ 
// 	$sql  = "SELECT YEAR(checked_date) AS chk_year, MONTH(checked_date) AS chk_month, COUNT(bag_id) AS chk_count ".
//             "FROM tested_donation ".
// 			"WHERE check_status = 1 ".
// 		    "GROUP BY YEAR(checked_date), MONTH(checked_date)";

// 	$result = $this->conn->query($sql);
// 	return $result;		
// 	}

// function get_Rejected(){
// 	$sql  = "SELECT YEAR(checked_date) AS chk_year, MONTH(checked_date) AS chk_month, COUNT(bag_id) AS chk_count ".
//             "FROM tested_donation ".
// 			"WHERE check_status = 0 ".
// 		    "GROUP BY YEAR(checked_date), MONTH(checked_date)";

// 	$result = $this->conn->query($sql);
// 	return $result;		
// 	}

// function get_CurrentBloods(){
// 	$sql  = "SELECT D.blood_grp, COUNT(donation.bag_id) AS don_count ".
// 	        "FROM donor D LEFT JOIN donation ON D.donor_id = donation.donor_id ".
// 			"GROUP BY D.blood_grp";

// 	$result = $this->conn->query($sql);
// 	return $result;		
// 	}				

// }


// class Inventory
// {

// 	function get_AddedBlood()
// 	{
//
// 		$sql  = "SELECT YEAR(added_date) AS add_year, MONTH(added_date) AS add_month, COUNT(added_date) AS add_count " .
// 			"FROM inventory " .
// 			"GROUP BY YEAR(added_date), MONTH(added_date)";

// 		$result = $this->conn->query($sql);
// 		return $result;
// 	}

// 	function get_IssuedBlood()
// 	{
//
// 		$sql  = "SELECT YEAR(issued_date) AS issue_year, MONTH(issued_date) AS issue_month, COUNT(issued_date) AS issue_count " .
// 			"FROM issue_history " .
// 			"GROUP BY YEAR(issued_date), MONTH(issued_date)";

// 		$result = $this->conn->query($sql);
// 		return $result;
// 	}

// 	function get_ExpiredBlood()
// 	{
//
// 		$sql  = "SELECT YEAR(expired_date) AS exp_year, MONTH(expired_date) AS exp_month, COUNT(expired_date) AS exp_count " .
// 			"FROM expired_bags " .
// 			"WHERE expired_date <= CURRENT_DATE " .
// 			"GROUP BY YEAR(expired_date), MONTH(expired_date)";

// 		$result = $this->conn->query($sql);
// 		return $result;
// 	}

// 	function get_CurrentInven()
// 	{
//
// 		$sql  = "SELECT blood_grp, COUNT(bag_id) as blcount FROM inventory " .
// 			"WHERE (issue_status = 1 AND block_status = 1) AND expire_date > CURRENT_DATE " .
// 			"GROUP BY blood_grp";

// 		$result = $this->conn->query($sql);
// 		return $result;
// 	}
// }


class Location
{
	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function get_allDistricts()
	{
		$sql  = "SELECT * FROM district ORDER BY district_name ASC";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_SpecificDisct($disc_id)
	{
		$sql  = "SELECT * FROM district " .
			"WHERE district_id = $disc_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	////////////////// Public Accesses /////////////////////
	public function give_allDistricts()
	{
		return $this->get_allDistricts();
	}
}
