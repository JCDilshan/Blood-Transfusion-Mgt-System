<?php
require('../assets/fpdf183/fpdf.php');

class MyPdf extends FPDF
{

	///////////////////////// Make page header /////////////////////////////
	function Header()
	{
		$this->Image('../images/icons/donor.png', 10, 6, 25);
		$this->SetFont('Arial', 'B', 15);
		$this->Cell(200, 10, 'LISTED DONORS', 0, 0, 'C');
		$this->Ln();
		$this->SetFont('Times', 'i', 14);
		$this->Cell(200, 10, 'BLOOD TRANSFUSION MANAGEMENT SYSTEM', 0, 0, 'C');
		$this->Ln(25);
	}

	///////////////////////// Make page footer /////////////////////////////
	function Footer()
	{
		$this->SetY(-60);

		$this->Terms_Singature();
		$this->SetFont('Arial', '', 10);
		$this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
	}

	function TableHeading()
	{
		$this->SetFont('Times', 'B', 12);
		$this->Cell(15, 12, '#ID', 1, 0, 'C');
		$this->Cell(60, 12, 'Donor Name', 1, 0, 'C');
		$this->Cell(22, 12, 'Group', 1, 0, 'C');
		$this->Cell(30, 12, 'District', 1, 0, 'C');
		$this->Cell(30, 12, 'Contact No.', 1, 0, 'C');
		$this->Cell(33, 12, 'Registered Date', 1, 0, 'C');
		$this->Ln();
	}

	///////////////////////// Page table body /////////////////////////////
	function TableBody()
	{
		include('../model/donor_model.php');
		$donor_object = new Donor($conn);
		$result = $donor_object->get_Alldonors();

		$this->SetFont('Times', '', 11);

		while ($row = $result->fetch_assoc()) {
			$this->Cell(15, 10, $row['donor_id'], 1, 0, 'C');
			$this->Cell(60, 10, $row['donor_name'], 1, 0, 'C');
			$this->Cell(22, 10, $row['grp_name'], 1, 0, 'C');

			include_once('../model/Data_model.php');
			$loc_object = new Location($conn);

			$loc_result = $loc_object->get_SpecificDisct($row['district_id']);
			$loc_row    = $loc_result->fetch_assoc();

			$this->Cell(30, 10, $loc_row['district_name'], 1, 0, 'C');


			$this->Cell(30, 10, $row['donor_contact'], 1, 0, 'C');
			$this->Cell(33, 10, $row['reg_date'], 1, 0, 'C');
			$this->Ln();
		}
	}

	///////////////////////// Set signature and date /////////////////////////////
	function Terms_Singature()
	{
		$this->Cell(80, 30, "", "", "1", "C");
		$this->SetFont('Arial', 'B', 12);
		$this->Cell(50, 10, "Terms and conditions", "", "1", "L");
		$this->SetFont("Arial", "", "9");
		$this->Cell(80, 10, "*This is computer generated document ", "", "1", "L");
		//$this->Cell(20, 10, " ", "", "1", "L");
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

$date = date("y-m-d");
$d1 = $date . "";
$filename = "ListedDonorReport_$d1.pdf";
//$path ="../document/userReport/$filename";

$pdf->Output('', $filename);
