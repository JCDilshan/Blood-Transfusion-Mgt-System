<?php
include_once('../common/db_connection.php');

class Camp
{
	private $conn;

	function __construct($conn)
	{
		$this->conn = $conn;
	}

	function add_camp($org_name, $location, $disc_id, $date, $time, $tar_donors, $other_info)
	{

		$sql  = "INSERT INTO camp(organizer_name,location,district_id,date,start_time,target_donors,other_info) " .
			"VALUES('$org_name','$location',$disc_id,'$date','$time','$tar_donors','$other_info')";

		$result = $this->conn->query($sql);
		return $result;
	}

	function edit_camp($camp_id, $org_name, $location, $date, $time, $tar_donors, $other_info)
	{

		$sql  = "UPDATE camp " .
			"SET organizer_name = '$org_name',location = '$location',date='$date',start_time='$time',target_donors=$tar_donors,other_info='$other_info' " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function getAllcamps()
	{

		$sql  = "SELECT * FROM camp";

		$result = $this->conn->query($sql);
		return $result;
	}

	function getAllHeldcamps()
	{

		$sql  = "SELECT * FROM camp " .
			"WHERE held_status = 1";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_specificCamp($camp_id)
	{

		$sql  = "SELECT * FROM camp " .
			"WHERE camp_id =" . $camp_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	function set_held($camp_id)
	{

		$sql  = "UPDATE camp " .
			"SET held_status = 1 " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function camp_finalize($camp_id, $par_donors, $remarks)
	{

		$sql  = "UPDATE camp " .
			"SET partici_donors = $par_donors, remark = '$remarks' " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	function remove_camp($camp_id)
	{

		$sql  = "DELETE FROM camp " .
			"WHERE camp_id=" . $camp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	protected function get_CampTB_AI()
	{

		$sql = "SHOW TABLE STATUS LIKE 'camp'";
		$result = $this->conn->query($sql);

		return $result;
	}

	////////////////////// Public Accesses //////////////////
	public function give_Allcamps()
	{
		return $this->getAllcamps();
	}

	public function give_AllHeldcamps()
	{
		return $this->getAllHeldcamps();
	}

	public function give_CampTB_AI()
	{
		return $this->get_CampTB_AI();
	}
}
