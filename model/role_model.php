<?php
include_once('../common/db_connection.php');

class Role
{
	private $conn;
	private $table = "role";

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	function getRoles()
	{

		$sql  = "SELECT * FROM $this->table";

		$result = $this->conn->query($sql);
		return $result;
	}

	function get_specificRole($role_id)
	{

		$sql  = "SELECT * FROM $this->table " .
			"WHERE role_id = $role_id";

		$result = $this->conn->query($sql);
		return $result;
	}
}
