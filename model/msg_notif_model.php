<?php
include_once('../common/db_connection.php');

class Message
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	protected function add_msg($msg_body, $sender, $receiver)
	{

		$sql  = "INSERT INTO messages(msg_body,sender_id,receiver_id) " .
			"VALUES('$msg_body',$sender,$receiver)";

		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function get_Messages($user_id)
	{
		$sql  = "SELECT * FROM messages " .
			"WHERE receiver_id = $user_id OR receiver_id = -1 ORDER BY sender_id";


		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function get_UnseenMsgs($user_id)
	{
		$sql  = "SELECT * FROM messages " .
			"WHERE (receiver_id = $user_id OR receiver_id = -1) AND seen_status = 0";


		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function get_SpecificMsgsBySender($user_id, $sender_id)
	{
		$sql  = "SELECT * FROM messages " .
			"WHERE sender_id = $sender_id AND (receiver_id = $user_id OR receiver_id = -1) " .
			"ORDER BY sent_time";


		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function set_seen($sender_id)
	{
		$sql  = "UPDATE messages " .
			"SET seen_status = 1 " .
			"WHERE sender_id = $sender_id";


		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function remove_BulkMsg($user_id, $sender_id)
	{
		$sql  = "DELETE FROM messages " .
			"WHERE sender_id = $sender_id AND (receiver_id = $user_id OR receiver_id = -1) ";


		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function remove_SingleMsg($msg_id)
	{
		$sql  = "DELETE FROM messages " .
			"WHERE msg_id = $msg_id";


		$result = $this->conn->query($sql);
		return	$result;
	}

	////////////////////// Public Access /////////////////////
	public function create_msg($msg_body, $sender, $receiver)
	{
		return $this->add_msg($msg_body, $sender, $receiver);
	}

	public  function give_Messages($user_id)
	{
		return $this->get_Messages($user_id);
	}

	public function give_SpecificMsgsBySender($user_id, $sender_id)
	{
		return $this->get_SpecificMsgsBySender($user_id, $sender_id);
	}

	public function give_UnseenMsgs($user_id)
	{
		return $this->get_UnseenMsgs($user_id);
	}

	public function update_seen($sender_id)
	{
		return $this->set_seen($sender_id);
	}

	public function delete_BulkMsg($user_id, $sender_id)
	{
		return $this->remove_BulkMsg($user_id, $sender_id);
	}

	public function delete_SingleMsg($msg_id)
	{
		return $this->remove_SingleMsg($msg_id);
	}
}


class Notification
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	protected function get_notif()
	{
		$sql  = "SELECT * FROM notification ORDER BY date_time DESC";

		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function get_unseenNotif()
	{
		$sql  = "SELECT * FROM notification " .
			"WHERE seen_status = 0";

		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function set_seen()
	{
		$sql  = "UPDATE notification SET seen_status = 1";

		$result = $this->conn->query($sql);
		return	$result;
	}

	protected function addNotif_InventoryDecline($blood_grp, $rem_count)
	{
		$sql  = "INSERT INTO notification(notif_body,date_time) VALUES(CONCAT('WARNING : The Number Of $blood_grp Type Blood Bags Has Dropped Below The Safe Level !, Currrently Remaining $blood_grp Bags Count :- $rem_count'),NOW())";

		$result = $this->conn->query($sql);
		return	$result;
	}

	/////////////// Public Access //////////////
	public function give_notif()
	{
		$result = $this->get_notif();
		return	$result;
	}

	public function give_unseenNotif()
	{
		return $this->get_unseenNotif();
	}

	public function update_seen()
	{
		return $this->set_seen();
	}

	public function createNotif_InventoryDecline($blood_grp, $rem_count)
	{
		return $this->addNotif_InventoryDecline($blood_grp, $rem_count);
	}
}
