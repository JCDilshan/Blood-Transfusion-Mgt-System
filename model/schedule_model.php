<?php
include_once('../common/db_connection.php');

class Hospital
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function add_hospital($name, $location, $contact, $email)
	{

		$sql  = "INSERT INTO hospital(hospital_name,location,contact_no,email) " .
			"VALUES('$name','$location','$contact','$email')";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function update_hospital($hosp_id, $contact, $email)
	{
		$sql  = "UPDATE hospital " .
			"SET contact_no='$contact',email='$email' " .
			"WHERE hospital_id = $hosp_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function hosp_exist($name, $loc)
	{

		$sql  = "SELECT * FROM hospital " .
			"WHERE hospital_name='$name' AND location='$loc'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_AllHospitals()
	{

		$sql  = "SELECT * FROM hospital";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_SpecificHospital($hosp_id)
	{

		$sql  = "SELECT * FROM hospital " .
			"WHERE hospital_id=" . $hosp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function remove_hospital($hosp_id)
	{

		$sql  = "DELETE FROM hospital " .
			"WHERE hospital_id=" . $hosp_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	/////////////// Public Accesses ////////////////////
	public function give_AllHospitals()
	{
		return $this->get_AllHospitals();
	}
}


class Schedule
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function add_schedule($hosp_id, $date, $str_time, $end_time, $tot_slots)
	{

		$sql  = "INSERT INTO app_schedule(hospital_id,set_date,start_time,end_time,total_slots,av_slots) " .
			"VALUES($hosp_id,'$date','$str_time','$end_time',$tot_slots,$tot_slots)";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_ListedSchedule()
	{

		$sql  = "SELECT * FROM app_schedule";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_dates($location)
	{

		$sql  = "SELECT set_date, SUM(av_slots) AS av_slots FROM app_schedule " .
			"WHERE hospital_id=" . $location . " " .
			"GROUP BY set_date";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_times($location, $date)
	{

		$sql  = "SELECT start_time,end_time,av_slots FROM app_schedule " .
			"WHERE hospital_id = $location AND set_date = '$date'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_specificShedule($schedule_id)
	{

		$sql  = "SELECT * FROM app_schedule " .
			"WHERE schedule_id=$schedule_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function find_ScheduleID($venue, $date, $time)
	{

		$sql  = "SELECT schedule_id FROM app_schedule " .
			"WHERE (hospital_id = $venue AND set_date = '$date') AND start_time = '$time'";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function update_schedule($schedule_id, $old_slots, $new_slots)
	{

		$sql  = "UPDATE app_schedule " .
			"SET total_slots = $new_slots, av_slots = IF($old_slots <= $new_slots, av_slots + ($new_slots - $old_slots), av_slots - ($old_slots - $new_slots)) " .
			"WHERE schedule_id = $schedule_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function decrease_slots($schedule_id)
	{

		$sql  = "UPDATE app_schedule " .
			"SET av_slots = av_slots - 1 " .
			"WHERE schedule_id =" . $schedule_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function increase_slots($schedule_id)
	{

		$sql  = "UPDATE app_schedule " .
			"SET av_slots = av_slots + 1 " .
			"WHERE schedule_id =" . $schedule_id . "";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function remove_schedule($schedule_id)
	{

		$sql  = "DELETE FROM app_schedule " .
			"WHERE schedule_id=" . $schedule_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	/////////////////// Public Accesses ////////////////////
	public function give_ListedSchedule()
	{
		return $this->get_ListedSchedule();
	}
}


class Appointment
{

	private $conn;

	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function set_appointment($schedule_id, $donor_nic)
	{

		$sql  = "INSERT INTO appointment(donor_nic,schedule_id) " .
			"VALUES('$donor_nic',$schedule_id)";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_AppointValidation($nic_no, $date)
	{

		$sql  = "SELECT * FROM appointment A " .
			"WHERE A.donor_nic = '$nic_no' AND (TIMESTAMPDIFF(DAY, A.added_date, '$date') < 90)";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_ListedAppointments()
	{

		$sql  = "SELECT * FROM appointment A INNER JOIN app_schedule S ON A.schedule_id = S.schedule_id";

		$result = $this->conn->query($sql);
		return $result;
	}

	public function cancel_Appointment($app_id)
	{

		$sql  = "DELETE FROM appointment " .
			"WHERE app_id = " . $app_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function count_AppointmentByScheduleID($schedule_id)
	{

		$sql  = "SELECT COUNT(app_id) AS counted FROM appointment " .
			"WHERE schedule_id = " . $schedule_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	public function get_AppointmentByScheduleID($schedule_id)
	{

		$sql  = "SELECT * FROM appointment A INNER JOIN donor D ON A.donor_nic = D.donor_nic " .
			"WHERE schedule_id = " . $schedule_id;

		$result = $this->conn->query($sql);
		return $result;
	}

	//////////////////// Public Accesses //////////////////////
	public function give_ListedAppointments()
	{
		return $this->get_ListedAppointments();
	}
}
