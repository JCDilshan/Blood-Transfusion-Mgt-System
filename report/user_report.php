<?php
require('../assets/fpdf183/fpdf.php');

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{
		$this->Image('../images/icons/user.png', 10, 6, 30);
		$this->SetFont('Arial', 'B', 15);
		$this->Cell(200, 10, 'USER REPORT', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Times', 'i', 14);
		$this->Cell(200, 10, 'BLOOD TRANSFUSION MANAGEMENT SYSTEM', 0, 0, 'C');
		$this->Ln(30);
	}

	///////////////////////// Make page footer /////////////////////////////
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial', '', 10);
		$this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
	}

	function TableHeading()
	{
		$this->SetFont('Times', 'B', 14);
		$this->Cell(30, 10, 'User ID', 1, 0, 'C');
		$this->Cell(88, 10, 'User Name', 1, 0, 'C');
		$this->Cell(30, 10, 'User Image', 1, 0, 'C');
		$this->Cell(40, 10, 'User Role', 1, 0, 'C');
		$this->Ln();
	}

	///////////////////////// Page table body /////////////////////////////
	function TableBody()
	{
		$this->SetFont('Times', '', 12);

		include('../model/user_model.php');
		$user_object = new User($conn);

		$result = $user_object->give_Allusers();
		$imgYpos = 62;

		include_once('../model/role_model.php');
		$role_object = new Role($conn);

		while ($row = $result->fetch_assoc()) {
			$this->Cell(30, 20, $row['user_id'], 1, 0, 'C');
			$this->Cell(88, 20, $row['fname'] . " " . $row['lname'], 1, 0, 'C');
			$this->Cell(30, 20, '', 1, 0, 'C');
			$this->Image('../images/users/' . $row['user_img'], 135, $imgYpos, 15, 15);


			$role_result = $role_object->get_specificRole($row['user_role']);
			$row_role = $role_result->fetch_assoc();

			$this->Cell(40, 20, $row_role['role_name'], 1, 0, 'C');
			$this->Ln();
			$imgYpos += 20;
		}
	}

	///////////////////////// Set signature and date /////////////////////////////
	function Terms_Singature()
	{
		$this->Cell(80, 30, "", "", "1", "C");
		$this->SetFont('Arial', 'B', 12);
		$this->Cell(80, 10, "Terms and conditions", "", "1", "L");
		$this->SetFont("Arial", "", "9");
		$this->Cell(80, 10, "*This is computer generated document ", "", "1", "L");
		$this->Cell(80, 10, " ", "", "1", "L");
		$this->Cell(80, 3, "..............................................", "", 0, "L");
		$date = date("Y-m-d");
		$this->Cell(100, 3, $date, "", 1, "R");
		$this->Cell(80, 7, "Manager", "", 0, "L");
		$this->Cell(95, 7, "Date", "", 1, "R");
	}
}

$pdf = new MyPdf();

///////////////////////// Execute methods /////////////////////////////
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->TableHeading();
$pdf->TableBody();
$pdf->Terms_Singature();

$date = date("y-m-d");
$d1 = $date . "";
$filename = "userReport_$d1.pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
