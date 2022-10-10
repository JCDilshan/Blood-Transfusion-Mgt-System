<?php
require('../assets/fpdf183/fpdf.php');

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{
		$this->Image('../images/icons/ostaff.jpg', 10, 6, 30);
		$this->SetFont('Arial', 'B', 15);
		$this->Cell(200, 10, 'REGISTERED OTHER STAFF', 0, 0, 'C');
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
		$this->Cell(28, 10, 'Member ID', 1, 0, 'C');
		$this->Cell(72, 10, 'Member Name', 1, 0, 'C');
		$this->Cell(35, 10, 'Contact No.', 1, 0, 'C');
		$this->Cell(55, 10, 'Email Address', 1, 0, 'C');
		$this->Ln();
	}

	///////////////////////// Page table body /////////////////////////////
	function TableBody()
	{
		include('../model/staff_model.php');
		$mem_object = new Other_Staff($conn);
		$result = $mem_object->get_AllMembers();

		$this->SetFont('Times', '', 12);

		if ($result->num_rows > 0) {

			while ($row = $result->fetch_assoc()) {
				$this->Cell(28, 10, $row['mem_id'], 1, 0, 'C');
				$this->Cell(72, 10, $row['fname'] . " " . $row['lname'], 1, 0, 'C');
				$this->Cell(35, 10, $row['contact_no'], 1, 0, 'C');
				$email = ($row['email'] == NULL) ? "N/A" : $row['email'];
				$this->Cell(55, 10, $email, 1, 0, 'C');
				$this->Ln();
			}
		} else {
			$this->Cell(190, 10, "No Records Found", 1, 0, 'C');
			$this->Ln();
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
$filename = "ListedOstaffReport_$d1.pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
