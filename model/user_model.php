<?php
include_once('../common/db_connection.php');

class User
{

	private $conn;
	private $table_user = "user";
	private $table_login = "login";

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	protected function add_user_userTB($fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg)
	{

		$sql  = "INSERT INTO $this->table_user(fname,lname,nic_no,dob,res_no,mno,gender,city,email,user_role,user_img) " .
			"VALUES('$fname','$lname','$nic','$dob','$resno','$mno','$gender','$city','$email',$role,'$uimg')";

		$this->conn->query($sql) or die($this->conn->error);
		$result = $this->conn->insert_id;
		return $result;
	}

	protected function add_user_logTB($user_id, $uname, $password)
	{
		$sql = "INSERT INTO $this->table_login(user_id, user_name, password) " .
			"VALUES($user_id,'$uname','$password')";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function user_search($nic)
	{
		$sql  = "SELECT * FROM $this->table_user " .
			"WHERE nic_no='$nic'";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function uname_search($uname)
	{
		$sql  = "SELECT * FROM $this->table_login " .
			"WHERE user_name='$uname'";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function email_search($email)
	{
		$sql  = "SELECT * FROM $this->table_user " .
			"WHERE email='$email'";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function get_info($user_id)
	{
		$sql  = "SELECT * FROM $this->table_user u, $this->table_login l " .
			"WHERE u.user_id=" . $user_id . " AND " .
			"l.user_id =" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function get_Allusers()
	{
		$sql  = "SELECT * FROM $this->table_user, $this->table_login " .
			"WHERE user.user_id=login.user_id";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function get_usersByRole($role_id)
	{
		$sql  = "SELECT * FROM $this->table_user " .
			"WHERE user_role = $role_id";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function change_info($user_id, $fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg)
	{
		$sql = "UPDATE $this->table_user " .
			"SET fname='$fname',lname='$lname',nic_no='$nic',dob='$dob',res_no='$resno',mno='$mno',gender='$gender',city='$city',email='$email',user_role=$role,user_img='$uimg' " .
			"WHERE user_id=" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function active_block($user_id, $switch_status)
	{
		$sql  = "UPDATE $this->table_user " .
			"SET user_status = $switch_status " .
			"WHERE user_id =" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	protected function remove_user($user_id)
	{
		$sql  = "DELETE FROM $this->table_user " .
			"WHERE user_id =" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);
		return $result;
	}

	///////////////////////// Public Accesses ////////////////////////

	public function create_user_userTB($fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg)
	{
		return $this->add_user_userTB($fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg);
	}

	public function create_user_logTB($user_id, $uname, $password)
	{
		return $this->add_user_logTB($user_id, $uname, $password);
	}

	public function update_info($user_id, $fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg)
	{
		return $this->change_info($user_id, $fname, $lname, $nic, $dob, $resno, $mno, $gender, $city, $email, $role, $uimg);
	}

	public function update_status($user_id, $switch_status)
	{
		return $this->active_block($user_id, $switch_status);
	}

	public function delete_user($user_id)
	{
		return $this->remove_user($user_id);
	}

	public function getUser_ByNIC($nic)
	{
		return $this->user_search($nic);
	}

	public function getUser_ByUname($uname)
	{
		return $this->uname_search($uname);
	}

	public function give_Allusers()
	{
		return $this->get_Allusers();
	}

	public function give_usersByRole($role_id)
	{
		return $this->get_usersByRole($role_id);
	}

	public function give_info($user_id)
	{
		return $this->get_info($user_id);
	}

	public function give_userByEmail($email)
	{
		return $this->email_search($email);
	}

	/////////////////////////////////////////// Login Methods ////////////////////////////////////////
	protected function user_login($uname, $password)
	{
		$sql  = "SELECT * FROM $this->table_user u, $this->table_login l " .
			"WHERE u.user_id = l.user_id AND (" .
			"(l.user_name ='" . $uname . "' OR " .
			"u.email ='" . $uname . "') AND " .
			"l.password ='" . $password . "')";

		$result = $this->conn->query($sql) or die($this->conn->error);

		return $result;
	}

	protected function set_login_stmt($user_id)
	{
		$sql  = "UPDATE $this->table_login " .
			"SET last_login = NOW(), " .
			"login_status = 1 " .
			"WHERE user_id=" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);

		return $result;
	}

	protected function pw_change($user_id, $new_pw)
	{
		$sql  = "UPDATE $this->table_login " .
			"SET password = '" . $new_pw . "' " .
			"WHERE user_id =" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);

		return $result;
	}

	protected function set_logout_stmt($user_id)
	{
		$sql  = "UPDATE $this->table_login " .
			"SET last_logout= NOW(), " .
			"login_status = 0 " .
			"WHERE user_id=" . $user_id . "";

		$result = $this->conn->query($sql) or die($this->conn->error);

		return $result;
	}

	/////////////////////////////// Public Access ////////////////////////////

	public function login($uname, $password)
	{
		return $this->user_login($uname, $password);
	}

	public function update_login_stmt($user_id)
	{
		return $this->set_login_stmt($user_id);
	}

	public function pw_update($user_id, $new_pw)
	{
		return $this->pw_change($user_id, $new_pw);
	}

	public function update_logout_stmt($user_id)
	{
		return $this->set_logout_stmt($user_id);
	}
}
