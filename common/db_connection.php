<?php

class DB
{
	private $conn;

	////////// Connection Configuration Variables //////////
	private $host = "localhost";
	private $user = "root";
	private $pass = "";
	private $db = "btms";

	////////// Bulid Connection //////////
	public function __construct()
	{
		$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db) or die("Connection Error");
	}

	////////// Return Bulit Connection //////////
	public function getConnection()
	{
		return $this->conn;
	}
}

$db_obj = new DB();

////////// Create Variable for Inherit Connection //////////
$conn = $db_obj->getConnection();
