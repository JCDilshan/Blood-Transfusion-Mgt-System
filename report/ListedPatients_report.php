<?php
require('../assets/fpdf183/fpdf.php');

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{
		$this->Image('../images/icons/elder.png', 10, 6, 30);
		$this->SetFont('Arial', 'B', 15);
		$this->Cell(200, 10, 'LISTED PATIENTS', 0, 0, 'C');
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
		$this->SetFont('Times', 'B', 12);
		$this->Cell(8, 0, '');
		$this->Cell(30, 12, 'Patient ID', 1, 0, 'C');
		$this->Cell(75, 12, 'Patient Name', 1, 0, 'C');
		$this->Cell(40, 12, 'N.I.C. Number', 1, 0, 'C');
		$this->Cell(30, 12, 'Blood Group', 1, 0, 'C');
		$this->Ln();
	}

	///////////////////////// Page table body /////////////////////////////
	function TableBody()
	{
		include('../model/patient_model.php');
		$pat_object = new Patient($conn);
		$result = $pat_object->get_Allpatients();

		$this->SetFont('Times', '', 11);

		while ($row = $result->fetch_assoc()) {
			$this->Cell(8, 0, '');
			$this->Cell(30, 10, $row['patient_id'], 1, 0, 'C');
			$this->Cell(75, 10, $row['patient_name'], 1, 0, 'C');
			$this->Cell(40, 10, $row['nic_no'], 1, 0, 'C');
			$this->Cell(30, 10, $row['grp_name'], 1, 0, 'C');
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
$filename = "ListedPatientReport_$d1.pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
